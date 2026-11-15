<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function myProfile()
    {
        $addresses = Address::where('profile_id', auth()->user()->profile->id)->get();
        return view('user.profile.index')
            ->with('addresses', $addresses);
    }

    public function template()
    {
        return redirect()->route('user.appearance', ['tab' => 'template']);
    }

    public function appearance(Request $request)
    {
        $profile = auth()->user()->profile;
        $currentTemplateId = max(1, min(10, (int) ($profile->template_id ?? 1)));
        $customizations = \App\Models\TemplateCustomization::where('user_id', auth()->id())
            ->orderBy('is_active', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();
        $activeCustomization = $customizations->where('is_active', true)->first();
        $tab = $request->get('tab', ($profile->appearance_mode ?? 'template') === 'custom' ? 'custom' : 'template');

        return view('user.appearance.index', [
            'currentTemplateId' => $currentTemplateId,
            'previewBaseUrl' => url(auth()->user()->username),
            'appearanceMode' => $profile->appearance_mode ?? 'template',
            'customizations' => $customizations,
            'activeCustomization' => $activeCustomization,
            'tab' => in_array($tab, ['template', 'custom'], true) ? $tab : 'template',
            'isBusiness' => auth()->user()->type === 'business',
        ]);
    }

    public function appearanceMode(Request $request)
    {
        $request->validate([
            'appearance_mode' => 'required|in:template,custom',
        ]);
        $profile = Profile::where('user_id', auth()->id())->first();
        if (!$profile) {
            return redirect()->route('user.appearance');
        }
        $mode = $request->appearance_mode;
        if ($mode === 'custom') {
            $customization = \App\Models\TemplateCustomization::getActiveForUser(auth()->id())
                ?: \App\Models\TemplateCustomization::where('user_id', auth()->id())->orderByDesc('updated_at')->first();
            if (!$customization) {
                alert()->error('Create a custom design first, then activate it.', 'Error');
                return redirect()->route('user.appearance', ['tab' => 'custom']);
            }
            if (!$customization->is_active) {
                $customization->activate();
            }
            $profile->customization_id = $customization->id;
            $profile->appearance_mode = 'custom';
            $profile->save();
            alert()->success('Live card now uses your custom design.', 'Success');
            return redirect()->route('user.appearance', ['tab' => 'custom']);
        }

        $profile->appearance_mode = 'template';
        $profile->save();
        \App\Models\TemplateCustomization::where('user_id', auth()->id())
            ->where('is_active', true)
            ->update(['is_active' => false]);
        alert()->success('Live card now uses your selected template.', 'Success');
        return redirect()->route('user.appearance', ['tab' => 'template']);
    }

    public function templateSave(Request $request)
    {
        $request->validate([
            'template_id' => 'required|integer|min:1|max:10',
        ]);
        $profile = Profile::where('user_id', auth()->id())->first();
        if ($profile) {
            $profile->template_id = (int) $request->template_id;
            $profile->appearance_mode = 'template';
            $profile->save();
        }
        \App\Models\TemplateCustomization::where('user_id', auth()->id())
            ->where('is_active', true)
            ->update(['is_active' => false]);

        alert()->success('Template updated. Your public card will use the selected template.', 'Success');
        return redirect()->route('user.appearance', ['tab' => 'template']);
    }

    public function myProfileSave(Request $request)
    {
        $rules = [];
        if (auth()->user()->type === 'business') {
            $rules['organization'] = 'required|string|max:255';
            $rules['designation'] = 'required|string|max:255';
        }
        $request->validate($rules);
        
        try {
            db::beginTransaction();
            $user = User::find(auth()->user()->id);
            $profile = Profile::where('user_id', auth()->user()->id)->first();
            if ($request->first_name) {
                $user->first_name = $request->first_name;
            }

            if ($request->last_name) {
                $user->last_name = $request->last_name;
            }

            if ($request->email) {
                $user->email = $request->email;
            }

            if ($request->phone) {
                $user->phone = $request->phone;
            }

            if ($request->organization) {
                $profile->organization = $request->organization;
            }

            if ($request->designation) {
                $profile->designation = $request->designation;
            }
            if ($request->website) {
                $profile->website = $request->website;
            }
            if ($request->bio) {
                $profile->bio = $request->bio;
            }

            if ($request->address) {
                $profile->address = $request->address;
            }
            if ($request->template_id) {
                $profile->template_id = $request->template_id;
            }

            if ($request->avatar) {
                $request->validate([
                    'avatar' => 'image|mimes:jpeg,png,jpg|max:2048',
                ]);
                $file = $request->file('avatar');
                $extension = $file->getClientOriginalExtension();
                $filename = getRandomString() . '-' . time() . '.' . $extension;
                $file->move('uploads/avatars', $filename);
                $path = 'uploads/avatars/' . $filename;
                $profile->avatar = $path;
            }

            if ($request->cover_image) {
                $request->validate([
                    'cover_image' => 'mimes:jpeg,png,jpg|max:2048',
                ]);
                $file = $request->file('cover_image');
                $extension = $file->getClientOriginalExtension();
                $filename = getRandomString() . '-' . time() . '.' . $extension;
                $file->move('uploads/cover_images', $filename);
                $background = 'uploads/cover_images/' . $filename;
                $profile->cover_image = $background;
            }

            $user->save();
            $profile->save();
            db::commit();

            alert()->success('Profile Updated Successfully', 'Success');
            return redirect()->back();

        } catch (\Throwable $th) {
            db::rollback();
            throw $th;

        }

    }

    public function saveMyAddress(Request $request)
    {
        $request->validate([
            'street_one' => 'required',
            'street_two' => 'nullable',
            'additional_info' => 'nullable',
            'postal_code' => 'required',
            'phone' => 'required',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'address_type' => 'required',
        ]);

        try {
            db::beginTransaction();
            $address = new Address();
            $address->profile_id = auth()->user()->profile->id;
            $address->street_one = $request->street_one;
            $address->street_two = $request->street_two;
            $address->flat_no = $request->flat_no;
            $address->postal_code = $request->postal_code;
            $address->phone = $request->phone;
            $address->city_id = $request->city_id;
            $address->state_id = $request->state_id;
            $address->country_id = $request->country_id;
            $address->additional_info = $request->additional_info;
            $address->address_type = $request->address_type;
            $address->created_by_id = auth()->user()->id;
            if ($request->is_primary == '1') {
                $find = Address::where('profile_id', auth()->user()->profile->id)->where('is_primary', 1)->first();
                if ($find) {
                    $find->is_primary = 0;
                    $find->save();
                }
                $address->is_primary = 1;
            } else {
                $address->is_primary = 0;
            }

            // if only one address, make it primary
            $count = Address::where('profile_id', auth()->user()->profile->id)->count();
            if ($count == 0) {
                $address->is_primary = 1;
            }

            $address->save();
            db::commit();
            alert()->success('Address Updated Successfully', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            db::rollback();
            throw $th;
        }
    }

    public function updateMyAddress(Request $request)
    {
        $request->validate([
            'street_one' => 'required',
            'street_two' => 'nullable',
            'additional_info' => 'nullable',
            'postal_code' => 'required',
            'phone' => 'required',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'address_type' => 'required',
        ]);
        $address = Address::find($request->address_id);
        if ($address && $address->profile_id != auth()->user()->profile->id) {
            alert()->success('You are not authorized to update this address', 'error');
            return redirect()->back();
        }

        try {
            db::beginTransaction();

            $address->street_one = $request->street_one;
            $address->street_two = $request->street_two;
            $address->flat_no = $request->flat_no;
            $address->postal_code = $request->postal_code;
            $address->phone = $request->phone;
            $address->city_id = $request->city_id;
            $address->state_id = $request->state_id;
            $address->country_id = $request->country_id;
            $address->additional_info = $request->additional_info;
            $address->address_type = $request->address_type;
            $address->updated_by_id = auth()->user()->id;
            if ($request->is_primary == '1') {
                $find = Address::where('profile_id', auth()->user()->profile->id)->where('is_primary', 1)->first();
                if ($find) {
                    $find->is_primary = 0;
                    $find->save();
                }
                $address->is_primary = 1;
            } else {
                $address->is_primary = 0;
            }

            $count = Address::where('profile_id', auth()->user()->profile->id)->count();
            if ($count < 2) {

                $address->is_primary = 1;
            }
            $address->save();
            db::commit();
            alert()->success('Address Updated Successfully', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            db::rollback();
            throw $th;
        }
    }

    public function getAddress(Request $request)
    {
        $address = Address::find($request->id);
        if ($address && $address->profile_id == auth()->user()->profile->id) {
            return view('user.profile.address._edit')
                ->with('address', $address);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($address);
    }
}
