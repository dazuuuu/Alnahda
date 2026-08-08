  <section class="section" id="register">
    <div class="container">
      <div class="application-header">
        <h2>Apply for Middle East Opportunities</h2>
        <p>Submit your information and our recruiters will connect you with roles that match your skills, experience, and career ambitions.</p>
      </div>

      <div class="application-layout">
        <form id="registration-form" class="application-form" method="post" action="<?= url('/api/applications') ?>" novalidate>

          <div class="form-section">
            <h3>Personal Details</h3>

            <div class="form-grid">
              <div class="form-row">
                <label for="fullname">Full Name *</label>
                <input type="text" id="fullname" name="fullname" required>
              </div>
              <div class="form-row">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-row">
                <label for="phone">Phone 1 *</label>
                <input type="tel" id="phone" name="phone" required pattern="[0-9+()\-\s]{7,}">
              </div>
              <div class="form-row">
                <label for="phone2">Phone 2 (optional)</label>
                <input type="tel" id="phone2" name="phone2" pattern="[0-9+()\-\s]{7,}">
              </div>
            </div>

            <div class="form-grid">
              <div class="form-row">
                <label for="age">Age *</label>
                <input type="number" id="age" name="age" min="18" max="65" required>
              </div>
              <div class="form-row">
                <label for="weight">Average Weight (kgs) *</label>
                <input type="number" id="weight" name="weight" required>
              </div>
            </div>

            <div class="form-grid">
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
              <div class="form-row">
                <label for="preferred-role">Preferred Job Category *</label>
                <select id="preferred-role" name="preferredRole" required>
                  <option value="">Select a category</option>
                  <option>HOUSEMAID</option>
                </select>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-row">
                <label for="gender">Gender *</label>
                <input type="text" id="gender" name="gender" required>
              </div>
              <div class="form-row">
                <label for="languages">Languages Spoken *</label>
                <input type="text" id="languages" name="languages" required placeholder="English, Arabic, Swahili...">
              </div>
            </div>
          </div>

          <div class="form-section">
            <h3>Work Information</h3>

            <div class="form-row form-row-radio">
              <label>Have you worked in SAUDIA ARABIA as a housemaid before? *</label>
              <div class="toggle-group">
                <input type="radio" name="travelledSaudia" value="yes" id="travelledSaudiaYes" required><label for="travelledSaudiaYes">Yes</label>
                <input type="radio" name="travelledSaudia" value="no" id="travelledSaudiaNo"><label for="travelledSaudiaNo">No</label>
              </div>
            </div>

            <div class="saudia-only">
              <div class="form-grid">
                <div class="form-row">
                  <label for="returnYear">Last Returned From SAUDIA ARABIA *</label>
                  <select id="returnYear" name="returnYear">
                    <option value="">Select year</option>
                  </select>
                </div>
                <div class="form-row">
                  <label for="durationYears">Years Worked in SAUDIA ARABIA *</label>
                  <input type="number" name="durationYears" id="durationYears" min="1" placeholder="e.g. 2">
                </div>
              </div>

              <div class="form-grid">
                <div class="form-row form-row-radio">
                  <label>Finished Your Contract? *</label>
                  <div class="toggle-group">
                    <input type="radio" name="finishedContract" value="yes" id="finishedContractYes"><label for="finishedContractYes">Yes</label>
                    <input type="radio" name="finishedContract" value="no" id="finishedContractNo"><label for="finishedContractNo">No</label>
                  </div>
                </div>
                <div class="form-row form-row-radio">
                  <label>Issue With Sponsor? *</label>
                  <div class="toggle-group">
                    <input type="radio" name="issueWithSponsor" value="yes" id="issueWithSponsorYes"><label for="issueWithSponsorYes">Yes</label>
                    <input type="radio" name="issueWithSponsor" value="no" id="issueWithSponsorNo"><label for="issueWithSponsorNo">No</label>
                  </div>
                </div>
              </div>

              <div class="form-row">
                <textarea id="contractExplain" class="hidden" name="contractExplain" rows="3" placeholder="Can you explain the nature of your issue?"></textarea>
              </div>

              <div class="form-grid">
                <div class="form-row form-row-radio">
                  <label>Were You Deported? *</label>
                  <div class="toggle-group">
                    <input type="radio" name="deported" value="yes" id="deportedYes"><label for="deportedYes">Yes</label>
                    <input type="radio" name="deported" value="no" id="deportedNo"><label for="deportedNo">No</label>
                  </div>
                </div>
                <div class="form-row form-row-radio">
                  <label>Given Final Exit? *</label>
                  <div class="toggle-group">
                    <input type="radio" name="exitVisa" value="yes" id="exitVisaYes"><label for="exitVisaYes">Yes</label>
                    <input type="radio" name="exitVisa" value="no" id="exitVisaNo"><label for="exitVisaNo">No</label>
                  </div>
                </div>
              </div>

              <div class="form-row form-row-radio">
                <label>Given Re-Entry Visa? *</label>
                <div class="toggle-group">
                  <input type="radio" name="reentryVisa" value="yes" id="reentryVisaYes"><label for="reentryVisaYes">Yes</label>
                  <input type="radio" name="reentryVisa" value="no" id="reentryVisaNo"><label for="reentryVisaNo">No</label>
                </div>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-row form-row-radio">
                <label>Traveled to Lebanon as a Housemaid? *</label>
                <div class="toggle-group">
                  <input type="radio" name="lebanon" value="yes" id="lebanonYes" required><label for="lebanonYes">Yes</label>
                  <input type="radio" name="lebanon" value="no" id="lebanonNo"><label for="lebanonNo">No</label>
                </div>
              </div>
              <div class="form-row form-row-radio">
                <label>Traveled to Jordan as a Housemaid? *</label>
                <div class="toggle-group">
                  <input type="radio" name="jordan" value="yes" id="jordanYes" required><label for="jordanYes">Yes</label>
                  <input type="radio" name="jordan" value="no" id="jordanNo"><label for="jordanNo">No</label>
                </div>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-row form-row-radio">
                <label>Are You Medically Fit? *</label>
                <div class="toggle-group">
                  <input type="radio" name="medicalFit" value="yes" id="medicalFitYes" required><label for="medicalFitYes">Yes</label>
                  <input type="radio" name="medicalFit" value="no" id="medicalFitNo"><label for="medicalFitNo">No</label>
                </div>
              </div>
              <div class="form-row form-row-radio" id="willingRow">
                <label id="willingLabel">Willing to Go to SAUDIA? *</label>
                <div class="toggle-group">
                  <input type="radio" name="willingToReturn" value="yes" id="willingToReturnYes" required><label for="willingToReturnYes">Yes</label>
                  <input type="radio" name="willingToReturn" value="no" id="willingToReturnNo"><label for="willingToReturnNo">No</label>
                </div>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-row form-row-radio">
                <label>Do You Have a Valid Passport? *</label>
                <div class="toggle-group">
                  <input type="radio" name="validPassport" value="yes" id="validPassportYes" required><label for="validPassportYes">Yes</label>
                  <input type="radio" name="validPassport" value="no" id="validPassportNo"><label for="validPassportNo">No</label>
                </div>
              </div>
              <div class="form-row form-row-radio">
                <label>Valid Certificate of Good Conduct? *</label>
                <div class="toggle-group">
                  <input type="radio" name="validConduct" value="yes" id="validConductYes" required><label for="validConductYes">Yes</label>
                  <input type="radio" name="validConduct" value="no" id="validConductNo"><label for="validConductNo">No</label>
                </div>
              </div>
            </div>

            <div class="form-row">
              <label for="appointmentPreference">When Can You Come to Our Offices for Appointment? *</label>
              <input type="date" id="appointmentPreference" name="appointmentPreference" required>
            </div>
          </div>

          <div class="form-row checkbox-row">
            <input type="checkbox" id="consent" name="consent" required>
            <label for="consent">I agree to be contacted by Al NAHDA Agency about overseas job openings.</label>
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
