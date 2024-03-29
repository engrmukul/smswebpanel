<!-- Footer
		============================================= -->
<footer id="footer" class="dark">
    <!-- Copyrights
    ============================================= -->
    <div id="copyrights">
        <div class="container">

            <div class="row justify-content-between col-mb-30">
                <div class="col-12 col-lg-auto text-center text-lg-left order-last order-lg-first">
                    <div id="footer-logo" class="flex-row">
                        <a href="{{ route('home') }}">
                            @if(!empty($config->logo) && file_exists("assets/images/".$config->logo))
                                <img class="standard-logo" alt="admin logo" src="{{ asset("assets/images/".$config->logo) }}">
                            @else
                                <img class="standard-logo" alt="admin logo" src="/assets/app-assets/images/logo/stack-logo-light.png">
                            @endif
                            <h2 class="brand-text">{{ $config->brand_name }}</h2>
                        </a>
                    </div><!-- #logo end -->
                    Copyrights &copy; 2020 - {{ date('Y') }} All Rights Reserved.
                </div>

                <div class="col-12 col-lg-auto text-center text-lg-right">
                    <div class="copyrights-menu copyright-links">
                        <ul class="top-links-container">
                            <li class="top-links-item"><a href="tel:{{ $config->phone }}"><i class="icon-phone3"></i> {{ $config->phone }}</a></li>
                            <li class="top-links-item"><a href="mailto:{{ $config->email }}" class="nott"><i class="icon-envelope2"></i>{{ $config->email }}</a></li>
                        </ul>
                    </div>
                    <a href="https://www.facebook.com/fastsmsportal" class="social-icon inline-block si-small si-borderless mb-0 si-facebook">
                        <i class="icon-facebook"></i>
                        <i class="icon-facebook"></i>
                    </a>

                    <a href="#" class="social-icon inline-block si-small si-borderless mb-0 si-twitter">
                        <i class="icon-twitter"></i>
                        <i class="icon-twitter"></i>
                    </a>
                </div>
            </div>

        </div>
    </div><!-- #copyrights end -->
</footer><!-- #footer end -->

<!-- Notify modal -->
<div class="modal text-left" id="notifyTerms" tabindex="-1" role="dialog" aria-labelledby="myModalLabelTerms" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-bold-700 danger" id="myModalLabelTerms">Terms And Conditions</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="fancy-title title-border title-center">
                    <h2><span style="text-decoration: underline;"><strong>Terms And Conditions</strong></span></h2>
                </div>
                <p style="text-align: center; color:red">সঠিকভাবে সম্পূর্ন কন্ডিশনস্ গুলো পড়ুন, অন্যাথায় কোনো কারনে আপনার অ্যাকাউন্ট সাসপেন্ড হলে কোনো রকম আপত্ত্বি/অনুরোধ গ্রহন করা হবে না ।</p>


                <ul>
                    <li style="text-align: justify;">* <span id="result_box2" lang="en">Your sent SMS will not be stored on our servers for a long time</span>.</li>
                    <li style="color: red; text-align: justify;">(আপনার পাঠানো এসএমএস আমাদের সার্ভার এ বেশিদিন জমা রাখা হয় না)</li>
                    <li><span id="result_box" lang="en">* If the new SMS   package is recharged before the old SMS package expires, the remaining   SMS in the account will be added to the new package.
	  But after the expiration of the recharge, the old SMS will not be added to the accoun</span>t.<br/></li>
                    <li style="color: red; text-align: justify;"> (পুরাতন এসএমএস প্যাকেজ মেয়াদ উত্তীর্ন হবার পূর্বে যদি নতুন এসএমএস প্যাকেজ রিচার্জ করা হয় তবে অ্যাকাউন্টে থাকা অবশিষ্ট এসএমএস নতুন প্যাকেজের সাথে যুক্ত হবে । মেয়াদ শেষ হবার পর রিচার্জ করিলে পুরাতন এসএমএস অ্যাকাউন্টে যুক্ত হবে না।)</li>
                    <li style="text-align: justify;">* <span id="result_box3" lang="en">Massage can not be used for any abuse, threats, or anti-national activities. You can not use our SMS for any illegal activities. If you do,  will take action against the law. And the account will be closed.</span></li>
                    <li style="color: red; text-align: justify;">(ম্যাসেজ কোনো গালিগালাজ, হুমকি, বা দেশ বিরোধী কোন কাজে পাঠাতে পারবেন না। আমাদের এসএমএস কোন বেআইনি কাজে ব্যাবহার করতে পারবেন না। করিলে আপনার বিরুদ্ধে আইন আনুক ব্যবস্থা গ্রহন করা হবে। এবং অ্যাকাউন্ট বন্ধ করে দেয়া হবে। )</li>
                    <li>* You are not allowed to abuse our service thus you can not send threat/love/slang/Adult/any kinds of personal SMS using our SMS service. If anyone do it we will take immediate action against him/her and his/her service will be canceled parmanently.<br/>
                    <li style="color: red; text-align: justify;">(আপনি এসএমএস সার্ভিস পার্সোনাল ব্যবহার যেমন বান্ধবীকে যোকোনো ধরনের ভালোবাসা সম্পর্কিত, অ্যাডাল্ট ম্যাসেজ পাঠাতে পারবেন না । যদি পাঠান সাথে সাথে অ্যাকাউন্ট সাসপেন্ড করে দেওয়া হবে এবং এ ক্ষেত্রে কোনো ধরনের রিকোয়েস্ট গ্রহনযোগ্য হবে না ।)</li>
                    <li><span id="result_box5" lang="en">* No fraudulent promotion sms can be sent (for example, winning the lottery)</span></li>
                    <li style="color:red"> (কোন রকম প্রতারনা মূলক প্রচারনার এসএমএস পাঠানো যাবে না (যেমন , লটারী জেতা)</li>
                    <li>* <span id="result_box4" lang="en">No adult  product can be marketed</span></li>
                    <li style="color: red; text-align: justify;">(কোনো অ্যাডাল্ট প্রোডাক্টের মার্কেটিং করা যাবে না )</li>
                    <li style="text-align: justify;">* SMS Sending speed maximum instant, but some time it take long for big volume. We request you to be patient and wait for sending complete.</li>
                    <li style="text-align: justify;">* <span id="result_box6" lang="en">You have to bear all the SMS responsibilities sent from your account. BulkSMSBD.COM will not be liable in any way for this. The action will be taken to bring a law against you for using any bad purpose. And the account will be closed.</span><br/>
                        <span style="color: red; text-align: justify;">(আপনার অ্যাকাউন্ট থেকে পাঠানো সকল এসএমএস আর দ্বায়ভার আপনাকে বহন করতে হবে। এর জন্য FASTSMSPORTAL.COM কোনো ভাবে দায়ী থাকবে না </span>)
                    </li>

                    <li>অ্যাকাউন্ট সাসপেন্ড হলে কোনোরকম টাকা ফেরত দেওয়া হবে না, আমরা প্রতিটি এসএমএস মনিটর করি এবং যে কোনো ধরনের বেআইনী কনটেন্ট এর জন্য অ্যাকাউন্ট সাসপেন্ড করা সহ কোন খারাপ উদ্দেশ্য ব্যাবহার করিলে আপনার বিরুদ্ধে আইন আনুক ব্যবস্থা গ্রহন করা হবে।</li>

                </ul>

                <form action="{{ route('terms.post') }}" method="post">
                    {{ csrf_field() }}
                    <p style="text-align: center;"><strong><span style="color:#008000;">I have read the above terms and conditions properly and i agree with each and every rule mentioned above, i will not break any of these terms and conditions.</span></strong></p>

                    <div style="background: rgb(238, 238, 238) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204); padding: 5px 10px; text-align: center;"><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><span style="font-size: 36px;"><button type="submit" class='button button2'><span><strong>I AGREE</strong></span></button></span></span>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <input type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal" value="close">
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->
