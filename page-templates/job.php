<?php
/**
 * Template Name: Job
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <section class="job-area">
        <?php
        $job_hero = chainthat_get_field('job_hero');
        $job_overview = chainthat_get_field('job_overview');
        $job_requirements = chainthat_get_field('job_requirements');
        $job_responsibilities = chainthat_get_field('job_responsibilities');
        $job_benefits = chainthat_get_field('job_benefits');
        $job_application = chainthat_get_field('job_application');
        ?>
        
        <div class="container">
            <div class="job-title">
                <h2 class="wow fadeInLeft"><?php echo esc_html($job_hero['title'] ?: 'Job Opening'); ?></h2>
                <p class="wow fadeInRight"><?php echo esc_html($job_hero['description']); ?></p>
            </div>
        </div>
    </section>

    <!-- job-overview-section -->
    <?php if ($job_overview) : ?>
        <section class="job-overview-area">
            <div class="container">
                <div class="job-overview-main">
                    <div class="job-overview-left wow fadeInLeft">
                        <h2><?php echo esc_html($job_overview['title']); ?></h2>
                        <p><?php echo esc_html($job_overview['description']); ?></p>
                        <div class="job-meta">
                            <div class="job-meta-item">
                                <span class="meta-label">Location:</span>
                                <span class="meta-value"><?php echo esc_html($job_overview['location']); ?></span>
                            </div>
                            <div class="job-meta-item">
                                <span class="meta-label">Type:</span>
                                <span class="meta-value"><?php echo esc_html($job_overview['type']); ?></span>
                            </div>
                            <div class="job-meta-item">
                                <span class="meta-label">Experience:</span>
                                <span class="meta-value"><?php echo esc_html($job_overview['experience']); ?></span>
                            </div>
                            <div class="job-meta-item">
                                <span class="meta-label">Salary:</span>
                                <span class="meta-value"><?php echo esc_html($job_overview['salary']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="job-overview-right wow fadeInRight">
                        <img src="<?php echo esc_url($job_overview['image']); ?>" alt="<?php echo esc_attr($job_overview['title']); ?>">
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- requirements-section -->
    <?php if ($job_requirements) : ?>
        <section class="requirements-area">
            <div class="container">
                <div class="requirements-title">
                    <h2 class="wow fadeInLeft"><?php echo esc_html($job_requirements['title']); ?></h2>
                    <p class="wow fadeInRight"><?php echo esc_html($job_requirements['description']); ?></p>
                </div>
                <div class="requirements-main">
                    <div class="requirements-left wow fadeInLeft">
                        <h3>Required Skills</h3>
                        <ul>
                            <?php
                            $required_skills = $job_requirements['required_skills'];
                            if ($required_skills) :
                                foreach ($required_skills as $skill) :
                            ?>
                                <li><?php echo esc_html($skill['skill']); ?></li>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>
                    <div class="requirements-right wow fadeInRight">
                        <h3>Preferred Skills</h3>
                        <ul>
                            <?php
                            $preferred_skills = $job_requirements['preferred_skills'];
                            if ($preferred_skills) :
                                foreach ($preferred_skills as $skill) :
                            ?>
                                <li><?php echo esc_html($skill['skill']); ?></li>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- responsibilities-section -->
    <?php if ($job_responsibilities) : ?>
        <section class="responsibilities-area">
            <div class="container">
                <div class="responsibilities-title">
                    <h2 class="wow fadeInLeft"><?php echo esc_html($job_responsibilities['title']); ?></h2>
                    <p class="wow fadeInRight"><?php echo esc_html($job_responsibilities['description']); ?></p>
                </div>
                <div class="responsibilities-main">
                    <ul>
                        <?php
                        $responsibilities = $job_responsibilities['responsibilities'];
                        if ($responsibilities) :
                            foreach ($responsibilities as $responsibility) :
                        ?>
                            <li class="wow fadeInUp"><?php echo esc_html($responsibility['responsibility']); ?></li>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- benefits-section -->
    <?php if ($job_benefits) : ?>
        <section class="benefits-area">
            <div class="container">
                <div class="benefits-title">
                    <h2 class="wow fadeInLeft"><?php echo esc_html($job_benefits['title']); ?></h2>
                    <p class="wow fadeInRight"><?php echo esc_html($job_benefits['description']); ?></p>
                </div>
                <div class="benefits-main">
                    <?php
                    $benefits = $job_benefits['benefits'];
                    if ($benefits) :
                        foreach ($benefits as $index => $benefit) :
                            $class = $index % 3 == 0 ? 'wow fadeInLeft' : ($index % 3 == 1 ? 'wow fadeInUp' : 'wow fadeInRight');
                    ?>
                        <div class="benefit-item <?php echo $class; ?>">
                            <div class="benefit-icon">
                                <img src="<?php echo esc_url($benefit['icon']); ?>" alt="<?php echo esc_attr($benefit['title']); ?>">
                            </div>
                            <div class="benefit-content">
                                <h4><?php echo esc_html($benefit['title']); ?></h4>
                                <p><?php echo esc_html($benefit['description']); ?></p>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- application-section -->
    <?php if ($job_application) : ?>
        <section class="application-area">
            <div class="container">
                <div class="application-title">
                    <h2 class="wow fadeInLeft"><?php echo esc_html($job_application['title']); ?></h2>
                    <p class="wow fadeInRight"><?php echo esc_html($job_application['description']); ?></p>
                </div>
                <div class="application-main">
                    <form action="" method="post" id="job-application-form">
                        <div class="application-form">
                            <div class="application-input-flex">
                                <?php
                                $form_fields = $job_application['form_fields'];
                                if ($form_fields) :
                                    $count = 0;
                                    foreach ($form_fields as $field) :
                                        $count++;
                                        $class = $count % 2 == 1 ? 'wow fadeInLeft' : 'wow fadeInRight';
                                ?>
                                    <div class="application-input <?php echo $class; ?>">
                                        <div class="application-input-title">
                                            <h4><?php echo esc_html($field['field_label']); ?></h4>
                                        </div>
                                        <?php if ($field['field_type'] == 'textarea') : ?>
                                            <textarea 
                                                name="<?php echo esc_attr($field['field_name']); ?>" 
                                                placeholder="<?php echo esc_attr($field['field_placeholder']); ?>"
                                                rows="4"
                                                <?php echo $field['field_required'] ? 'required' : ''; ?>
                                            ></textarea>
                                        <?php else : ?>
                                            <input 
                                                type="<?php echo esc_attr($field['field_type']); ?>" 
                                                name="<?php echo esc_attr($field['field_name']); ?>" 
                                                placeholder="<?php echo esc_attr($field['field_placeholder']); ?>"
                                                <?php echo $field['field_required'] ? 'required' : ''; ?>
                                            >
                                        <?php endif; ?>
                                    </div>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                            
                            <div class="application-file wow fadeInLeft">
                                <div class="application-input-title">
                                    <h4>Upload CV</h4>
                                </div>
                                <div class="file-upload">
                                    <input type="file" name="cv_upload" id="cv_upload" accept=".pdf,.doc,.docx" required>
                                    <label for="cv_upload" class="file-upload-label">
                                        <span class="file-upload-text">Choose File</span>
                                        <span class="file-upload-icon">📁</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="application-file wow fadeInRight">
                                <div class="application-input-title">
                                    <h4>Upload Cover Letter (Optional)</h4>
                                </div>
                                <div class="file-upload">
                                    <input type="file" name="cover_letter_upload" id="cover_letter_upload" accept=".pdf,.doc,.docx">
                                    <label for="cover_letter_upload" class="file-upload-label">
                                        <span class="file-upload-text">Choose File</span>
                                        <span class="file-upload-icon">📁</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="application-checkbox wow fadeInLeft">
                                <div class="checkbox">
                                    <input type="checkbox" id="job_terms" required>
                                    <label for="job_terms">I accept <a href="<?php echo esc_url(chainthat_get_option('terms_url', '#')); ?>">Terms & Conditions.</a> Check our <a href="<?php echo esc_url(chainthat_get_option('privacy_url', '#')); ?>">Privacy Policy</a></label>
                                </div>
                            </div>
                            
                            <div class="application-submit wow fadeInRight">
                                <button type="submit"><?php echo esc_html($job_application['submit_button_text'] ?: 'Submit Application'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>



