<footer>
    <div class="container">
        <div class="row">
            <div id="cberlogo-copyright" class="col-md-3 col-12 pl-md-0">
                <a href="https://www.bsu.edu/cber">
                    <img src="/data_center/img/BallStateCBER-red.png" alt="Ball State Logo"/>
                    <span class="sr-only">
                        Ball State University: Center for Business and Economic Research
                    </span>
                </a>
                <p>
                    &copy; Center for Business and Economic Research, Ball State University
                </p>
            </div>
            <section class="col-md-5 pl-md-0">
                <?php if ($this->fetch('footer_about')): ?>
                    <?= $this->fetch('footer_about') ?>
                <?php else: ?>
                    <h2>About Ball State CBER Data Center</h2>
                    <p>
                        Ball State CBER Data Center is one-stop shop for economic data including demographics, education, health, and social
                        capital. Our easy-to-use, visual web tools offer data collection and analysis for grant writers, economic developers, policy
                        makers, and the general public.
                    </p>
                    <p>
                        Ball State CBER Data Center (<a href="https://cberdata.org">cberdata.org</a>) is a product of the Center for Business and Economic Research at Ball State
                        University. CBER's mission is to conduct relevant and timely public policy research on a wide range of economic issues
                        affecting the state and nation. <a href="https://www.bsu.edu/cber">Learn more about CBER.</a>
                    </p>
                <?php endif; ?>
                <p>
                    <a href="https://cberdata.org/terms">Terms of Service</a>
                </p>
            </section>
            <section class="col-md-4 col-12">
                <h2>
                    Center for Business and Economic Research
                </h2>
                <address>
                    Ball State University<br />
                    Whitinger Business Building, room 149<br />
                    2000 W. University Ave.<br />
                    Muncie, IN 47306-0360
                </address>

                <dl>
                    <div>
                        <dt>Phone:</dt>
                        <dd>765-285-5926</dd>
                    </div>

                    <div>
                        <dt>Email:</dt>
                        <dd><a href="mailto:cber@bsu.edu">cber@bsu.edu</a></dd>
                    </div>

                    <div>
                        <dt>Website:</dt>
                        <dd><a href="https://www.bsu.edu/cber">www.bsu.edu/cber</a></dd>
                    </div>
                </dl>

                <p id="social-media-links">
                    <a href="https://www.facebook.com/BallStateCBER">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://www.twitter.com/BallStateCBER">
                        <i class="fab fa-twitter"></i>
                    </a>
                </p>
            </section>
        </div>
    </div>
</footer>
