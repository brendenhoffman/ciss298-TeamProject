<div id="main" class="container py-4">
  <h2>Submit Your Testimonial</h2>

  <!-- Testimonial Form -->
  <form id="testimonialForm" class="loadForm">
    <div class="mb-3">
      <label for="name" class="form-label"><strong>Your Name:</strong></label>
      <input type="text" name="name" id="name" class="form-control" size="30" maxlength="30" required>
    </div>

    <div class="mb-3">
      <label for="comment" class="form-label"><strong>Your Comments:</strong></label>
      <textarea name="comment" id="comment" class="form-control" rows="7" cols="35" required></textarea>
    </div>

    <div class="mb-3">
      <label for="myPhoto" class="form-label"><strong>Upload File:</strong></label>
      <input type="file" id="myPhoto" name="myPhoto" class="form-control" accept="image/*" required>
    </div>

    <!-- Buttons: side by side, spaced apart -->
    <div class="d-flex justify-content-between">
      <button type="submit" name="submit" class="btn btn-success">Submit</button>
      <a class="btn btn-primary" href="#" onclick="loadPage('viewTestimonials.php')">View Submitted Testimonials</a>
    </div>
  </form>
