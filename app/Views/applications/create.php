<?php
$title = 'SLGTI Online Application Portal';
$lead = 'Submit your application securely for the next SLGTI intake.';
require APP_PATH . '/Views/partials/page-hero.php';
?>
<nav class="container breadcrumb-nav application-breadcrumb" aria-label="Breadcrumb"><a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><a href="<?= e(url('admissions')) ?>">Admissions</a><span aria-hidden="true">/</span><span>Apply online</span></nav>
<section class="application-portal section">
    <div class="container">
        <div class="application-portal-intro">
            <div><p class="eyebrow">Official SLGTI admissions portal</p><h2>Complete your application with confidence</h2><p>Choose an available programme, provide your personal and education details, then submit your application for review by the admissions office.</p></div>
            <div class="application-status <?= $applyOpen ? 'is-open' : 'is-closed' ?>"><i class="bi <?= $applyOpen ? 'bi-unlock' : 'bi-lock' ?>"></i><span><strong><?= $applyOpen ? 'Applications open' : 'Applications closed' ?></strong><small><?= $applyOpen ? 'Online submissions are currently accepted.' : 'Please watch Admissions for the next intake.' ?></small></span></div>
        </div>
        <?php if (!empty($applicationFunctions)): ?>
            <section class="public-application-functions application-services-panel" aria-labelledby="application-services-title">
                <div class="section-heading"><p class="eyebrow">Official services</p><h2 id="application-services-title">Other application services</h2><p>Use an external service only when it matches your request.</p></div>
                <div class="public-application-grid"><?php foreach ($applicationFunctions as $function): ?><a class="public-application-card" href="<?= e($function['external_url']) ?>"<?= $function['open_new_tab'] ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><span class="public-application-icon"><i class="bi <?= e($function['icon']) ?>" aria-hidden="true"></i></span><span><strong><?= e($function['name']) ?></strong><small><?= $function['description'] ? e(strip_tags($function['description'])) : e($function['application_type']) ?></small></span><i class="bi bi-arrow-up-right" aria-hidden="true"></i></a><?php endforeach; ?></div>
            </section>
        <?php endif; ?>
        <div class="application-layout">
            <aside class="application-sidebar">
                <div class="application-progress" aria-label="Application steps"><p class="eyebrow">Application steps</p><ol><li class="is-current"><span>1</span><div><strong>Choose programme</strong><small>Select your department and pathway.</small></div></li><li><span>2</span><div><strong>Applicant details</strong><small>Tell us about yourself and your education.</small></div></li><li><span>3</span><div><strong>Submit for review</strong><small>Upload an optional supporting document.</small></div></li></ol></div>
                <div class="application-help"><i class="bi bi-shield-check"></i><strong>Your information is protected</strong><p>Applications are reviewed by authorised SLGTI staff. Keep your reference number after submission.</p><a href="<?= e(url('admissions#faq')) ?>">Read admission FAQs <i class="bi bi-arrow-right"></i></a></div>
            </aside>
            <div>
                <?php if (!$applyOpen): ?>
                    <div class="application-closed-card"><i class="bi bi-calendar-x"></i><h2>Online applications are currently closed</h2><p>Please watch the admission announcements for the next intake or contact the admissions office for guidance.</p><a class="btn btn-navy" href="<?= e(url('admissions')) ?>">Admission information</a></div>
                <?php else: ?>
                    <form class="form-card wide application-form" method="post" action="<?= e(url('apply')) ?>" id="applyForm" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <p class="hp"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
                        <div class="application-form-heading"><p class="eyebrow">Step 1 of 3</p><h2>Programme selection</h2><p>Select a published programme. Its level and duration will appear below.</p></div>
                        <div class="form-row">
                            <label>Department
                                <select id="departmentSelect"><option value="">All departments</option><?php foreach ($departments as $department): ?><option value="<?= (int) $department['id'] ?>"><?= e($department['name']) ?></option><?php endforeach; ?></select>
                            </label>
                            <label>Programme
                                <select name="programme_id" id="programmeSelect" required data-selected="<?= e((string) old('programme_id')) ?>"><option value="">Select a programme</option><?php foreach ($programmes as $programme): ?><option value="<?= (int) $programme['id'] ?>" data-department="<?= (int) $programme['department_id'] ?>" data-level="<?= e($programme['level_name']) ?>" data-code="<?= e($programme['level_code']) ?>" data-duration="<?= e($programme['duration']) ?>" data-qualification="<?= e($programme['qualification']) ?>" <?= (string) old('programme_id') === (string) $programme['id'] ? 'selected' : '' ?>><?= e($programme['title']) ?> — <?= e($programme['level_code']) ?></option><?php endforeach; ?></select>
                                <?php if ($err = field_error('programme_id')): ?><small class="error"><?= e($err) ?></small><?php endif; ?>
                            </label>
                        </div>
                        <div class="programme-selection-summary" id="programmeSummary" hidden><div><span class="badge" data-summary-code></span><strong data-summary-title></strong></div><dl><div><dt>Qualification</dt><dd data-summary-qualification></dd></div><div><dt>Duration</dt><dd data-summary-duration></dd></div></dl><div class="programme-requirement-hints" data-programme-requirements></div></div>
                        <div class="application-form-heading"><p class="eyebrow">Step 2 of 3</p><h2>Applicant information</h2><p>Use your official identification and contact details.</p></div>
                        <div class="form-row"><label>Full name <input name="full_name" required autocomplete="name" value="<?= e((string) old('full_name')) ?>"><?php if ($err = field_error('full_name')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label><label>NIC / passport number <input name="nic" required autocomplete="off" value="<?= e((string) old('nic')) ?>" placeholder="200012345678 or 123456789V"><?php if ($err = field_error('nic')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label></div>
                        <div class="form-row"><label>Email <input type="email" name="email" required autocomplete="email" value="<?= e((string) old('email')) ?>"><?php if ($err = field_error('email')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label><label>Phone <input name="phone" required autocomplete="tel" value="<?= e((string) old('phone')) ?>"><?php if ($err = field_error('phone')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label></div>
                        <div class="form-row"><label>Gender<select name="gender" required><option value="">Select</option><?php foreach (['Female', 'Male', 'Other'] as $gender): ?><option <?= old('gender') === $gender ? 'selected' : '' ?>><?= e($gender) ?></option><?php endforeach; ?></select><?php if ($err = field_error('gender')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label><label>Date of birth <input type="date" name="date_of_birth" required value="<?= e((string) old('date_of_birth')) ?>"><?php if ($err = field_error('date_of_birth')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label></div>
                        <div class="form-row"><label>Address <input name="address" required autocomplete="street-address" value="<?= e((string) old('address')) ?>"><?php if ($err = field_error('address')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label><label>District <input name="district" required value="<?= e((string) old('district')) ?>"><?php if ($err = field_error('district')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label></div>
                        <div class="form-row"><label>Highest education<select name="education_level" required><option value="">Select</option><?php foreach (['GCE O/L', 'GCE A/L', 'NVQ Level 4', 'Other'] as $level): ?><option <?= old('education_level') === $level ? 'selected' : '' ?>><?= e($level) ?></option><?php endforeach; ?></select><?php if ($err = field_error('education_level')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label><label>Results summary <input name="results" required value="<?= e((string) old('results')) ?>" placeholder="Example: O/L 6 passes including Maths and Science"><?php if ($err = field_error('results')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label></div>
                        <label>Additional information <textarea name="message" rows="4"><?= e((string) old('message')) ?></textarea></label>
                        <div class="application-form-heading"><p class="eyebrow">Step 3 of 3</p><h2>Supporting document and submission</h2><p>Upload a PDF or image if requested. The existing application system validates file type and size securely.</p></div>
                        <label>Supporting document (PDF or image, optional) <input type="file" name="document" accept="application/pdf,image/jpeg,image/png,image/webp"><?php if ($err = field_error('document')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
                        <button class="btn btn-red application-submit" type="submit"><i class="bi bi-send"></i> Submit application</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
