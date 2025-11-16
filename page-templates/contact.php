<?php
/**
 * Template Name: Contact
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); ?>

<section class="contact-area">
     <form action="">
    <div class="container">
        <div class="contact-title">
            <h2 class="wow fadeInLeft">Contact <span class="d-none d-lg-inline">Us</span></h2>
            <p class="wow fadeInRight">ChainThat is the activating ingredient for agile insurance<br> organisations. Get in touch. We'd love to talk.</p>
        </div>
        <div class="contact-main">
         <div class="tuch-form">
                <div class="tuch-input-flex">
                    <div class="tuch-input wow fadeInLeft">
                        <div class="tuch-input-title">
                            <h4>First Name</h4>
                        </div>
                        <input type="text" placeholder="First Name">
                    </div>
                    <div class="tuch-input wow fadeInRight">
                         <div class="tuch-input-title">
                            <h4>Email</h4>
                        </div>
                        <input required type="email" placeholder="Email">
                    </div>
                    <div class="tuch-input wow fadeInLeft">
                         <div class="tuch-input-title">
                            <h4>Phone</h4>
                        </div>
                        <input type="text" placeholder="000 000 0000">
                    </div> 
                    <div class="tuch-input wow fadeInRight">
                         <div class="tuch-input-title">
                            <h4>Company</h4>
                        </div>
                        <input type="text" placeholder="Company Name">
                    </div>
                </div>
                <div class="tuch-textarea wow fadeInLeft">
                     <div class="tuch-input-title">
                        <h4>Message</h4>
                    </div>
                    <textarea rows="4" placeholder="Message..."></textarea>
                </div>
                <div class="tuch-checkbox wow fadeInRight">
                    <div class="checkbox"><input type="checkbox" id="check"><label for="check">I accept <a href="#">Terms & Conditions.</a> Check our <a href="#">Privacy Policy</a></label></div>
                </div>
                <div class="tuch-submit wow fadeInLeft">
                    <button type="submit">submit</button>
                </div>
            </div>
        </div>
    </div>
   </form>
</section>

<!-- office-section -->
<section class="office-area">
    <div class="container">
        <div class="office-title">
            <h2 class="wow fadeInLeft">Our Offices</h2>
            <p class="wow fadeInRight">Contact any of our  offices below or email us at <a href="mailto:info@chainthat.com">info@chainthat.com</a></p>
        </div>
        <div class="office-main">
            <div class="office-item">
                <div class="office-left wow fadeInLeft">
                    <h4>UNITED KINGDOM</h4>
                    <h2>LONDON</h2>
                </div>
                <div class="office-right wow fadeInRight">
                    <p>8 Lloyd's Avenue, First Floor, London EC3N 3EL England</p>
                    <a href="mailto:info@chainthat.com">info@chainthat.com</a>
                    <ul>
                        <li><img src="<?php echo get_template_directory_uri(); ?>/images/location.svg" alt=""></li>
                         <li><a href="#">View Location</a></li>
                    </ul>
                </div>
            </div>
            <div class="office-item">
                 <div class="office-left wow fadeInLeft">
                    <h4>INDIA</h4>
                    <h2>BENGALURU</h2>
                </div>
                <div class="office-right wow fadeInRight">
                    <p>2nd floor, Novel MSR Park, Marathahalli, Bengaluru, 560037</p>
                    <a href="tel:911244653000">+91 124 465 3000</a>
                    <ul>
                        <li><img src="<?php echo get_template_directory_uri(); ?>/images/location.svg" alt=""></li>
                         <li><a href="#">View Location</a></li>
                    </ul>
                </div>
            </div>
            <div class="office-item">
                 <div class="office-left wow fadeInLeft">
                    <h4>INDIA</h4>
                    <h2>GURUGRAM</h2>
                </div>
                <div class="office-right wow fadeInRight">
                    <p>Building 6, 4th Floor Candor Tech Space, Sector 48 Gurgaon, 122018</p>
                     <a href="tel:911244653000">+91 124 465 3000</a>
                    <ul>
                        <li><img src="<?php echo get_template_directory_uri(); ?>/images/location.svg" alt=""></li>
                         <li><a href="#">View Location</a></li>
                    </ul>
                </div>
            </div>
            <div class="office-item">
                 <div class="office-left wow fadeInLeft">
                    <h4>INDIA</h4>
                    <h2>NOIDA</h2>
                </div>
                <div class="office-right wow fadeInRight">
                    <p>Candor Techspace, 12th floor,Tower 3, Block B, Industrial Area, Sector 62, Noida 201309</p>
                    <a href="tel:911244653000">+91 124 465 3000</a>
                    <ul>
                        <li><img src="<?php echo get_template_directory_uri(); ?>/images/location.svg" alt=""></li>
                        <li><a href="#">View Location</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>