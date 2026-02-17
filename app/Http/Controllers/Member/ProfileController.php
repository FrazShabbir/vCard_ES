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
        $currentTemplateId = (int) (auth()->user()->profile->template_id ?? 1);
        $currentTemplateId = max(1, min(10, $currentTemplateId));
        $username = auth()->user()->username;
        $previewBaseUrl = url($username);
        return view('user.profile.template', [
            'currentTemplateId' => $currentTemplateId,
            'previewBaseUrl' => $previewBaseUrl,
        ]);
    }

    public function templateSave(Request $request)
    {
        $request->validate([
            'template_id' => 'required|integer|min:1|max:10',
        ]);
        $profile = Profile::where('user_id', auth()->id())->first();
        if ($profile) {
            $profile->template_id = (int) $request->template_id;
            $profile->save();
        }
        alert()->success('Template updated. Your public card will use the selected design.', 'Success');
        return redirect()->route('user.profile.template');
    }
    public function myProfileSave(Request $request)
    {
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
