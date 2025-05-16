<?php
function html_footer_full(): string
{
    ob_start(); ?>
    <footer class="full-footer">
        <div class="footer-sections">
            <div class="footer-col">
                <h4>WSJ Membership</h4>
                <ul>
                    <li>The Journal Collection</li>
                    <li>Subscriptions Option</li>
                    <li>Why Subscribe?</li>
                    <li>Corporate Subscriptions</li>
                    <li>WSJ Higher Education Program</li>
                    <li>WSJ High School Program</li>
                    <li>Public Library Program</li>
                    <li>WSJ Live</li>
                    <li>Commercial Partnerships</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Customer Service</h4>
                <ul>
                    <li>Customer Center</li>
                    <li>Contact Us</li>
                    <li>Cancel My Subscription</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Tools & Features</h4>
                <ul>
                    <li>Newsletters & Alerts</li>
                    <li>Guides</li>
                    <li>Topics</li>
                    <li>My News</li>
                    <li>RSS Feeds</li>
                    <li>Video Center</li>
                    <li>Watchlist</li>
                    <li>Podcasts</li>
                    <li>Visual Stories</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>ADS</h4>
                <ul>
                    <li>Advertise</li>
                    <li>Commercial Real Estate ADS</li>
                    <li>Place a Classified A</li>
                    <li>Sell Your Business</li>
                    <li>Sell Your Home</li>
                    <li>Recruitment & Career Ads</li>
                    <li>Digital Self Service</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>More</h4>
                <ul>
                    <li>About Us</li>
                    <li>Content Partnerships</li>
                    <li>Corrections</li>
                    <li>Jobs at WSJ.</li>
                    <li>News Archive</li>
                    <li>Register for Free</li>
                    <li>Reprints & Licensing</li>
                    <li>Buy Issues</li>
                    <li>WSJ Shop</li>
                    <li>Down Jones Press Room</li>
                    <li>Down Jones Smart Money</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <!-- <p>Copyright ©<?= date('Y') ?> Dow Jones & Company, Inc. All Rights Reserved.</p> -->
        </div>
    </footer>
    <?php return ob_get_clean();
}
?>
