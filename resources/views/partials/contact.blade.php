<section class="section-padding bg-light" id="contact">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Contact Us</span>
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">
                Have questions or want to place an order? Reach out to us anytime.
            </p>
        </div>

        <div class="row g-5 align-items-start">

            {{-- Contact Info --}}
            <div class="col-lg-5">
                <div class="contact-info">
                    @foreach([
                        ['icon' => 'bi-geo-alt-fill',  'title' => 'Address',       'text' => 'Barra, Opol, Misamis Oriental, Philippines', 'link' => null],
                        ['icon' => 'bi-facebook',       'title' => 'Facebook Page', 'text' => 'facebook.com/AquaPureBarra', 'link' => 'https://facebook.com/AquaPureBarra'],
                        ['icon' => 'bi-messenger',      'title' => 'Messenger',     'text' => 'm.me/AquaPureBarra',         'link' => 'https://m.me/AquaPureBarra'],
                        ['icon' => 'bi-envelope-fill',  'title' => 'Email',         'text' => 'aquapure.barra@gmail.com',   'link' => 'mailto:aquapure.barra@gmail.com'],
                        ['icon' => 'bi-clock-fill',     'title' => 'Business Hours','text' => 'Mon–Sat: 6AM–8PM | Sun: 7AM–5PM', 'link' => null],
                    ] as $info)
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="bi {{ $info['icon'] }}"></i>
                        </div>
                        <div>
                            <h6>{{ $info['title'] }}</h6>
                            @if($info['link'])
                                <a href="{{ $info['link'] }}" class="contact-link"
                                   target="_blank" rel="noopener noreferrer">
                                    {{ $info['text'] }}
                                </a>
                            @else
                                <p>{{ $info['text'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <a href="https://m.me/AquaPureBarra" target="_blank"
                       rel="noopener noreferrer" class="btn btn-messenger w-100 mt-3">
                        <i class="bi bi-messenger me-2"></i>Chat on Messenger
                    </a>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="col-lg-7">
                <div class="contact-form-box">
                    <h4 class="mb-4 fw-bold">
                        <i class="bi bi-chat-left-fill text-primary me-2"></i>
                        Send Us a Message
                    </h4>

                    {{-- Success / Error Flash --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="full_name">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                       id="full_name" name="full_name"
                                       placeholder="e.g. Juan dela Cruz"
                                       value="{{ old('full_name') }}" required>
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="phone_number">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                       id="phone_number" name="phone_number"
                                       placeholder="e.g. 09XX-XXX-XXXX"
                                       value="{{ old('phone_number') }}" required>
                                <div class="invalid-feedback">Please enter a phone number.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="email_address">
                                    Email Address
                                </label>
                                <input type="email" class="form-control"
                                       id="email_address" name="email_address"
                                       placeholder="e.g. yourname@email.com"
                                       value="{{ old('email_address') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="subject">
                                    Subject <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('subject') is-invalid @enderror"
                                        id="subject" name="subject" required>
                                    <option value="" disabled {{ old('subject') ? '' : 'selected' }}>
                                        Select a subject
                                    </option>
                                    @foreach([
                                        '📦 Bulk / Business Order',
                                        '❓ General Question',
                                        '💬 Feedback / Suggestion',
                                        '⚠️ Complaint / Issue',
                                        '🤝 Partnership Request',
                                        '📝 Other',
                                    ] as $opt)
                                    <option value="{{ $opt }}" {{ old('subject') === $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a subject.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="message">
                                    Message <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message" name="message"
                                          rows="4"
                                          placeholder="Type your message here..."
                                          required>{{ old('message') }}</textarea>
                                <div class="invalid-feedback">Please enter your message.</div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-custom w-100 py-3">
                                    <i class="bi bi-send-fill me-2"></i>Send Message
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>