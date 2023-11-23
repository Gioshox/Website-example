// Document ready function to ensure the DOM is fully loaded before executing the code
$(document).ready(function() {

    // Form submission event handler
    $(".my-form").submit(function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Serialize form data into a query string
        const formData = $(this).serialize();

        // Get the data-action attribute of the form
        const formAction = $(this).data("action");

        // Initialize the URL variable
        let url;

        // Determine the appropriate URL based on the form action
        if (formAction === "login") {
            url = "../php/login.php"; 
        } else if (formAction === "register") {
            url = "../php/register.php"; 
        }

        // AJAX request to handle form submission
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            success: function(response) {
                // Log the response to the console
                console.log(response);

                // Check if the response contains a success message
                if (response.success) {
                    // Display a success toast notification
                    toastr["success"](response.success, "Success");
                    console.log("success");

                    // If needed, perform a redirection after a successful response
                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 5000);
                    }
                } else if (response.error) {
                    // Display an error toast notification
                    toastr["error"](response.error, "Error");
                }
            },
            error: function(xhr, status, error) {
                // Log and display an error message if the AJAX request fails
                console.error(xhr, status, error);
                toastr["error"](error, "Error");
            }
        });
    });
});
