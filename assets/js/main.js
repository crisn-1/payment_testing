$(document).ready(function() {
    // CTA button click handler
    $('#ctaButton').on('click', function() {
        $(this).text('Loading...');
        
        setTimeout(() => {
            alert('Welcome! This is where your journey begins.');
            $(this).text('Get Started');
        }, 500);
    });

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
