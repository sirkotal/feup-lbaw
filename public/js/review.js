function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
function initializeStarRating() {
    const stars = document.querySelectorAll('.star-rating i');
    const ratingInput = document.getElementById('rating');

    stars.forEach((star) => {
        star.addEventListener('mouseover', function () {
            resetStars();
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function () {
            resetStars();
            const currentRating = ratingInput.value;
            highlightStars(currentRating);
        });

        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            ratingError.textContent = '';
        });
    });

    function resetStars() {
        stars.forEach((star) => {
            star.classList.remove('fa', 'fa-star', 'checked');
        });
    }

    function highlightStars(rating) {
        stars.forEach((star) => {
            const starRating = star.getAttribute('data-rating');
            if (starRating <= rating) {
                star.classList.add('fa', 'fa-star', 'checked');
            } else {
                star.classList.add('fa', 'fa-star');
            }
        });
    }
}

function initializeStarRatingForEdit() {
    const stars = document.querySelectorAll('.star-rating-input i');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach((star) => {
        star.addEventListener('mouseover', function () {
            resetStars();
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function () {
            resetStars();
            const currentRating = ratingInput.value;
            highlightStars(currentRating);
        });

        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            ratingError.textContent = '';
        });
    });

    function resetStars() {
        stars.forEach((star) => {
            star.classList.remove('fa', 'fa-star', 'checked');
        });
    }

    function highlightStars(rating) {
        stars.forEach((star) => {
            const starRating = star.getAttribute('data-rating');
            if (starRating <= rating) {
                star.classList.add('fa', 'fa-star', 'checked');
            } else {
                star.classList.add('fa', 'fa-star');
            }
        });
    }
}

function validateRating() {
    const ratingInput = document.getElementById('rating');
    const ratingError = document.getElementById('ratingError');

    if (ratingInput.value === '0') {
        ratingError.textContent = 'Please select a rating.';
        return false;
    }

    return true;
}

function validateEditRating() {
    const ratingInput = document.getElementById('ratingInput');
    const ratingError = document.getElementById('ratingErrorInput');

    if (ratingInput.value === '0') {
        ratingError.textContent = 'Please select a rating.';
        return false;
    }

    return true;
}

function upvoteReview() {
    let upvoteButtons = document.querySelectorAll('.upvoteButton');

    console.log('test');

    upvoteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            let data = {
                'review_id': button.getAttribute('review_id'),
            };
            sendAjaxRequest('post', '/upvote_review/' + data.review_id, {}, upvoteReviewHandler);
        });
    });
}


function upvoteReviewHandler() {
    let response = JSON.parse(this.responseText);

    console.log('test22');

    let clickedButton = document.getElementById(`upvoteButton_${response.review_id}`);
    
    if (clickedButton) {
        clickedButton.classList.toggle('liked');
        let icon = clickedButton.querySelector('i');
        if (icon) {
            icon.className = `fa ${clickedButton.classList.contains('liked') ? 'fa-thumbs-up' : 'fa-thumbs-o-up'}`;
        }
    }

    let upvoteCountElement = document.getElementById(`upvoteCount_${response.review_id}`);
    if (upvoteCountElement) {
        upvoteCountElement.textContent = `Upvotes: ${response.upvoteCount}`;
    }

}

function deleteReview(reviewId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#00754D',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            sendAjaxRequest('post', `/delete_review/${reviewId}`, {}, deleteReviewHandler);
        }
    });
}

function deleteReviewHandler() {
    let response = JSON.parse(this.responseText);
    console.log(response);

    if (response.success) {
        Swal.fire({
            title: 'Review Deleted!',
            icon: 'success',
            confirmButtonColor: '#00754D',
        }).then(() => {
            location.reload();
        });
    } else if (response.error) {
        Swal.fire({
            title: 'Error',
            text: response.error,
            icon: 'error',
        });
    }
}

function reportReview(reviewId) {
    Swal.fire({
        title: 'Report Review',
        input: 'select',
        inputOptions: {
            'spam': 'Spam',
            'inappropriate_content': 'Inappropriate Content',
            'misinformation': 'Misinformation',
            'discriminatory': 'Discriminatory',
            'other': 'Other',
        },
        inputPlaceholder: 'Select a reason',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        confirmButtonColor: '#00754D',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            sendAjaxRequest('post', '/report_review/' + reviewId, {reason: result.value}, reportReviewHandler);
        }
    });
}

function reportReviewHandler() {
    let response = JSON.parse(this.responseText);
    console.log(response);

    if (response.success) {
        Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.onmouseenter = Swal.stopTimer;
              toast.onmouseleave = Swal.resumeTimer;
            }
          }).fire({
            icon: "success",
            title: "Review reported successfully"
          });
    } else if (response.error) {
        Swal.fire({
            title: 'Error',
            text: response.error,
            icon: 'error',
            confirmButtonColor: '#00754D',
        });
    }
}


function toggleEditMode() {
    document.getElementById('editTitle').style.display = 'none';
    document.getElementById('editTitleInput').style.display = 'block';

    document.getElementById('editRating').style.display = 'none';
    document.getElementById('editRatingInput').style.display = 'block';

    document.getElementById('editReviewText').style.display = 'none';
    document.getElementById('editReviewTextInput').style.display = 'block';

    document.getElementById('editButton').style.display = 'none';
    document.getElementById('deleteReviewButton').style.display = 'none';
    document.getElementById('saveButton').style.display = 'block';
    document.getElementById('cancelButton').style.display = 'block';
}

function cancelEdit() {
    document.getElementById('editTitle').style.display = 'block';
    document.getElementById('editTitleInput').style.display = 'none';

    document.getElementById('editRating').style.display = 'block';
    document.getElementById('editRatingInput').style.display = 'none';

    document.getElementById('editReviewText').style.display = 'block';
    document.getElementById('editReviewTextInput').style.display = 'none';

    document.getElementById('editButton').style.display = 'block';
    document.getElementById('deleteReviewButton').style.display = 'block';
    document.getElementById('saveButton').style.display = 'none';
    document.getElementById('cancelButton').style.display = 'none';

    document.getElementById('ratingErrorInput').textContent = '';
}

function saveChanges(reviewId) {
    const title = document.getElementById('editTitleInput').value;
    const rating = document.getElementById('ratingInput').value;
    const reviewText = document.getElementById('editReviewTextInput').value;

    console.log(title);
    console.log(rating);
    console.log(reviewText);

    if (validateEditRating()) {
        const data = {
            title: title,
            rating: rating,
            review_text: reviewText
        };

        sendAjaxRequest('post', `/update_review/${reviewId}`, data, saveChangesHandler);
    }
}

function saveChangesHandler() {
    let response = JSON.parse(this.responseText);
    console.log(response);

    if (response.success) {
        Swal.fire({
            title: 'Review updated!',
            icon: 'success',
            confirmButtonColor: '#00754D',
        }).then(() => {
            location.reload();
        });
    } else if (response.error) {
        Swal.fire({
            title: 'Error',
            text: response.error,
            icon: 'error',
            confirmButtonColor: '#00754D',
        });
    }

    cancelEdit();
}

function redirectToLoginUpvote(){
    Swal.fire({
        icon: "error",
        title: "You have to be logged in to upvote!",
        showCancelButton: true,
        confirmButtonColor: "#00754D",
        cancelButtonColor: "#d33",
        confirmButtonText: "<a href='/login' style='text-decoration: none; color: white;'> Login/Register </a>"
    });
}


document.addEventListener('DOMContentLoaded', initializeStarRating);
document.addEventListener('DOMContentLoaded', initializeStarRatingForEdit);
upvoteReview();