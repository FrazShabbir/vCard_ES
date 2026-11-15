{{-- Render custom sections --}}
@if(isset($sections) && $sections->isNotEmpty())
    {{-- Debug: Uncomment to see sections count --}}
    {{-- <div style="display:none;">Sections count: {{ $sections->count() }}</div> --}}
    @foreach($sections as $section)
        @php
            $bgStyle = '';
            if ($section->background_image) {
                $bgStyle = 'background-image: url(' . asset($section->background_image) . '); background-size: cover; background-position: center;';
            } elseif ($section->background_color) {
                $bgStyle = 'background-color: ' . $section->background_color . ';';
            }
            $textStyle = $section->text_color ? 'color: ' . $section->text_color . ';' : '';
        @endphp
        
        <section class="custom-section custom-section-{{ $section->section_type }}" 
                 style="{{ $bgStyle }} {{ $textStyle }}"
                 data-section-type="{{ $section->section_type }}">
            <div class="container" style="padding: 3rem 1rem;">
                @if($section->title)
                    <h2 class="section-title" style="text-align: center; margin-bottom: 1rem; font-size: 2rem; font-weight: 700;">
                        {{ $section->title }}
                    </h2>
                @endif
                @if($section->subtitle)
                    <p class="section-subtitle" style="text-align: center; margin-bottom: 2rem; font-size: 1.1rem; opacity: 0.9;">
                        {{ $section->subtitle }}
                    </p>
                @endif
                
                @if($section->section_type === 'banner')
                    <div class="section-banner-content" style="text-align: center;">
                        {!! $section->content !!}
                    </div>
                
                @elseif($section->section_type === 'team')
                    <div class="section-team-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; margin-top: 2rem;">
                        @foreach($section->teamMembers as $member)
                            <div class="team-member-card" style="background: rgba(255,255,255,0.95); border-radius: 12px; padding: 1.5rem; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                @if($member->avatar)
                                    <img src="{{ asset($member->avatar) }}" alt="{{ $member->name }}" 
                                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem;">
                                @else
                                    <div style="width: 120px; height: 120px; border-radius: 50%; background: #e0e0e0; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #999;">
                                        <i class="las la-user"></i>
                                    </div>
                                @endif
                                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">{{ $member->name }}</h3>
                                @if($member->position)
                                    <p style="color: #666; margin-bottom: 0.75rem; font-weight: 500;">{{ $member->position }}</p>
                                @endif
                                @if($member->bio)
                                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">{{ Str::limit($member->bio, 100) }}</p>
                                @endif
                                @if($member->social_links && is_array($member->social_links))
                                    <div class="team-member-socials" style="display: flex; gap: 0.5rem; justify-content: center;">
                                        @foreach($member->social_links as $social)
                                            @if(isset($social['url']))
                                                <a href="{{ $social['url'] }}" target="_blank" style="color: #0d6efd; font-size: 1.25rem;">
                                                    <i class="las la-{{ $social['icon'] ?? 'link' }}"></i>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                
                @elseif($section->section_type === 'services')
                    <div class="section-services-content" style="margin-top: 2rem;">
                        {!! $section->content !!}
                    </div>
                
                @elseif($section->section_type === 'testimonials')
                    <div class="section-testimonials-content" style="margin-top: 2rem;">
                        {!! $section->content !!}
                    </div>
                
                @elseif($section->section_type === 'gallery')
                    <div class="section-gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
                        @php
                            $images = is_array($section->content) ? $section->content : [];
                            if (is_string($section->content)) {
                                $images = json_decode($section->content, true) ?? [];
                            }
                        @endphp
                        @foreach($images as $image)
                            @if(isset($image['url']))
                                <div class="gallery-item" style="border-radius: 8px; overflow: hidden; aspect-ratio: 1;">
                                    <img src="{{ asset($image['url']) }}" alt="{{ $image['alt'] ?? '' }}" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endif
                        @endforeach
                    </div>
                
                @elseif($section->section_type === 'cta')
                    <div class="section-cta-content" style="text-align: center; margin-top: 2rem;">
                        {!! $section->content !!}
                    </div>
                
                @elseif($section->section_type === 'features')
                    <div class="section-features-content" style="margin-top: 2rem;">
                        {!! $section->content !!}
                    </div>
                
                @elseif($section->section_type === 'about')
                    <div class="section-about-content" style="max-width: 800px; margin: 2rem auto; text-align: center;">
                        {!! $section->content !!}
                    </div>
                
                @elseif($section->section_type === 'contact')
                    <div class="section-contact-content" style="max-width: 600px; margin: 2rem auto;">
                        {!! $section->content !!}
                    </div>
                
                @else
                    <div class="section-generic-content" style="margin-top: 2rem;">
                        {!! $section->content !!}
                    </div>
                @endif
            </div>
        </section>
    @endforeach
@endif
