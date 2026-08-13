  <section class="section" id="register">
    <div class="container">
      <div class="application-header">
        <h2>Apply for Middle East Opportunities</h2>
        <p>Submit your information and our recruiters will connect you with roles that match your skills, experience, and career ambitions.</p>
      </div>

      <div class="application-layout">
        <form id="registration-form" class="application-form" method="post" action="<?= url('/api/applications') ?>" novalidate>

          <div class="form-section">
            <h3>Application Details</h3>

            <div class="form-grid">
              <div class="form-row">
                <label for="fullname">Name *</label>
                <input type="text" id="fullname" name="fullname" required>
              </div>
              <div class="form-row">
                <label for="age">Age *</label>
                <input type="number" id="age" name="age" min="18" max="65" required>
              </div>
            </div>

            <div class="form-row form-row-radio">
              <label>Do You Have Valid Passport? *</label>
              <div class="toggle-group">
                <input type="radio" name="validPassport" value="yes" id="validPassportYes" required><label for="validPassportYes">Yes</label>
                <input type="radio" name="validPassport" value="no" id="validPassportNo"><label for="validPassportNo">No</label>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-row">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" required pattern="[0-9+()\-\s]{7,}">
              </div>
              <div class="form-row">
                <label for="county">County *</label>
                <select id="county" name="county" required>
                  <option value="">Select your county</option>
                  <option>Baringo</option><option>Bomet</option><option>Bungoma</option><option>Busia</option>
                  <option>Elgeyo-Marakwet</option><option>Embu</option><option>Garissa</option><option>Homa Bay</option>
                  <option>Isiolo</option><option>Kajiado</option><option>Kakamega</option><option>Kericho</option>
                  <option>Kiambu</option><option>Kilifi</option><option>Kirinyaga</option><option>Kisii</option>
                  <option>Kisumu</option><option>Kitui</option><option>Kwale</option><option>Laikipia</option>
                  <option>Lamu</option><option>Machakos</option><option>Makueni</option><option>Mandera</option>
                  <option>Marsabit</option><option>Meru</option><option>Migori</option><option>Mombasa</option>
                  <option>Murang'a</option><option>Nairobi City</option><option>Nakuru</option><option>Nandi</option>
                  <option>Narok</option><option>Nyamira</option><option>Nyandarua</option><option>Nyeri</option>
                  <option>Samburu</option><option>Siaya</option><option>Taita-Taveta</option><option>Tana River</option>
                  <option>Tharaka-Nithi</option><option>Trans Nzoia</option><option>Turkana</option><option>Uasin Gishu</option>
                  <option>Vihiga</option><option>Wajir</option><option>West Pokot</option>
                </select>
              </div>
            </div>

            <div class="form-row form-row-radio">
              <label>Have You Ever Travelled To Saudia Before As Housemaid? *</label>
              <div class="toggle-group">
                <input type="radio" name="travelledSaudia" value="yes" id="travelledSaudiaYes" required><label for="travelledSaudiaYes">Yes</label>
                <input type="radio" name="travelledSaudia" value="no" id="travelledSaudiaNo"><label for="travelledSaudiaNo">No</label>
              </div>
            </div>

            <div class="form-row">
              <label for="appointmentPreference">When Would You Like To Visit Our Office? *</label>
              <input type="date" id="appointmentPreference" name="appointmentPreference" required>
            </div>
          </div>

          <div class="application-actions">
            <button type="submit" class="btn btn-full application-submit">Submit Application</button>
          </div>

          <p class="form-feedback" role="status" aria-live="polite"></p>
        </form>
      </div>

      <p class="application-portal-hint">
        Already applied? <a href="<?= url('/portal/login') ?>">Sign in to your dashboard</a> to track your application status and messages from our team.
      </p>
    </div>
  </section>
