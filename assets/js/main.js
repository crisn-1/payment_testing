$(document).ready(function() {
    // CTA button click handler
    $('#ctaButton').on('click', function() {
        $(this).text('Loading...');
        
        setTimeout(() => {
            alert('Welcome! This is where your journey begins.');
            $(this).text('Get Started');
        }, 500);
    });

    // Smooth scroll for navigation links
    $('.nav-links a').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        
        $('html, body').animate({
            scrollTop: $(target).offset()?.top || 0
        }, 800);
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
