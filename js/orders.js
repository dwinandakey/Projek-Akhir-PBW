function confirmCancellation(id) {
    var confirmation = confirm("Are you sure you want to cancel this order?");

    if (confirmation) {
        window.location.href = "cancel-order.php?id=" + id; // Change to the appropriate page for processing cancellation
    }
}

function confirmDeletion(id) {
    var confirmation = confirm("Are you sure you want to delete this order?");

    if (confirmation) {
        window.location.href = "delete-order.php?id=" + id; // Change to the appropriate page for processing deletion
    }
}

function confirmCancellationCleaning(id) {
    var confirmation = confirm("Are you sure you want to cancel this cleaning order?");

    if (confirmation) {
        window.location.href = "cancel-cleaning.php?id=" + id;
    }
}

function confirmDeletionCleaning(id) {
    var confirmation = confirm("Are you sure you want to delete this cleaning order?");

    if (confirmation) {
        window.location.href = "delete-cleaning.php?id=" + id;
    }
}

function confirmCancellationRental(id) {
    var confirmation = confirm("Are you sure you want to cancel this rental order?");

    if (confirmation) {
        window.location.href = "cancel-rental.php?id=" + id;
    }
}

function confirmDeletionRental(id) {
    var confirmation = confirm("Are you sure you want to delete this rental order?");

    if (confirmation) {
        window.location.href = "delete-rental.php?id=" + id;
    }
}