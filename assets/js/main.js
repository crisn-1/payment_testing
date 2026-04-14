$(document).ready(function() {
    // Add hover effect to nav links
    $('.nav-links a').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
});
