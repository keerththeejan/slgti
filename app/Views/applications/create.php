<?php $title = 'Apply now'; $lead = 'Online application for the next SLGTI intake.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container">
        <?php if (settings('apply_open', '1') !== '1'): ?>
            <p class="alert alert-warning">Online applications are currently closed.</p>
        <?php else: ?>
        <form class="form-card wide" method="post" action="<?= e(url('apply')) ?>" id="applyForm" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <p class="hp"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
            <div class="form-row">
                <label>Full name <input name="full_name" required value="<?= e((string) old('full_name')) ?>"><?php if ($err = field_error('full_name')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
                <label>NIC <input name="nic" required value="<?= e((string) old('nic')) ?>" placeholder="200012345678 or 123456789V"><?php if ($err = field_error('nic')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            </div>
            <div class="form-row">
                <label>Email <input type="email" name="email" required value="<?= e((string) old('email')) ?>"><?php if ($err = field_error('email')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
                <label>Phone <input name="phone" required value="<?= e((string) old('phone')) ?>"><?php if ($err = field_error('phone')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            </div>
            <div class="form-row">
                <label>Gender
                    <select name="gender" required>
                        <option value="">Select</option>
                        <?php foreach (['Female', 'Male', 'Other'] as $gender): ?>
                            <option <?= old('gender') === $gender ? 'selected' : '' ?>><?= e($gender) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($err = field_error('gender')): ?><small class="error"><?= e($err) ?></small><?php endif; ?>
                </label>
                <label>Date of birth <input type="date" name="date_of_birth" required value="<?= e((string) old('date_of_birth')) ?>"><?php if ($err = field_error('date_of_birth')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            </div>
            <div class="form-row">
                <label>Address <input name="address" required value="<?= e((string) old('address')) ?>"><?php if ($err = field_error('address')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
                <label>District <input name="district" required value="<?= e((string) old('district')) ?>"><?php if ($err = field_error('district')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            </div>
            <div class="form-row">
                <label>Department
                    <select id="departmentSelect">
                        <option value="">All departments</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= (int) $department['id'] ?>"><?= e($department['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Programme
                    <select name="programme_id" id="programmeSelect" required data-selected="<?= e((string) old('programme_id')) ?>">
                        <option value="">Select a programme</option>
                        <?php foreach ($programmes as $programme): ?>
                            <option value="<?= (int) $programme['id'] ?>" data-department="<?= (int) $programme['department_id'] ?>" <?= (string) old('programme_id') === (string) $programme['id'] ? 'selected' : '' ?>><?= e($programme['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($err = field_error('programme_id')): ?><small class="error"><?= e($err) ?></small><?php endif; ?>
                </label>
            </div>
            <div class="form-row">
                <label>Highest education
                    <select name="education_level" required>
                        <option value="">Select</option>
                        <?php foreach (['GCE O/L', 'GCE A/L', 'NVQ Level 4', 'Other'] as $level): ?>
                            <option <?= old('education_level') === $level ? 'selected' : '' ?>><?= e($level) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($err = field_error('education_level')): ?><small class="error"><?= e($err) ?></small><?php endif; ?>
                </label>
                <label>Results summary <input name="results" required value="<?= e((string) old('results')) ?>" placeholder="Example: O/L 6 passes including Maths and Science"><?php if ($err = field_error('results')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            </div>
            <label>Additional information <textarea name="message" rows="4"><?= e((string) old('message')) ?></textarea></label>
            <label>Supporting document (PDF or image, optional) <input type="file" name="document" accept="application/pdf,image/jpeg,image/png,image/webp"><?php if ($err = field_error('document')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            <button class="btn btn-red" type="submit">Submit application</button>
        </form>
        <?php endif; ?>
    </div>
</section>
