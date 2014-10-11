<html>
    <head>
        <?php echo $head; ?>
    </head>
    <body>
        <?php echo $header; ?>
    <div>
        <div>
            <div></div>
            <div>
                <h1><br></h1>
            </div>
            <div></div>
            <div></div>
            <div>
                <div></div>
            </div>
            <div></div>
        </div><!--mainMessage ends-->


        <div>
            <div>
                <div>&#x1F465;</div>
                <div>
                    <!--<h2>Take advantage of this exclusive offer and join thousands of users improving their career skills with us.</h2>-->
                    <h2><b>End of Summer Offer</b></h2>
                    <p style="padding-left:15px;">Start a new course at 90% OFF! Offer expires at midnight on Friday 10th October.</p>
                </div>
                <div></div>
            </div><!--mainTitle ends-->

            <ul>
                <li>
                    <div><span>Unlimited access to all courses</span> <div></div></div>
                    <div>
                        <div>
                            <div>
                                <p>Unlimited access</p>
                                <p>Was <span>&pound;</span>199 per month</p>
                                <p>Now <span>&pound;</span><span><?php echo $allPrice;?></span> per month</p>
                                <p>Discount 92.5%</p>
                            </div>
                            <div>
                                <div>
                                    <div><?php echo $allPrice;?>">Buy now for <span>&pound;</span><span><?php echo $allPrice;?></span>/month</div>
                                </div>
                            </div>
                            <div></div>
                        </div>
                        <div></div>
                    </div><!--offerContent ends-->
                </li><!--offerOne ends-->

                <li>
                    <div>Option 2: <span>Pick and choose from the courses below for annual licences</span> <div></div></div>
                    <div>
                        <?php echo $listOfCourses; ?>
                    </div><!--offerContent ends-->
                </li><!--offerTwo ends-->
            </ul><!--offer ends-->
        </div><!--mainOfferContainer ends-->

        <div>
            <div>
            </div>
            <div>
                <div>
                    <p></p>
                    <p>You will get immediate access until you cancel your subscription</p>
                    <p>Subscription price: <span></span></p>
                </div>
            </div>
            <div></div>
        </div><!--paymentFormContainer ends-->

    </div><!--mainContainer ends-->
    <div></div>
    <div></div><div></div><div></div><div></div>
    
     <div>
        <div><span>&#x2421;</span></div>
        <div></div>
    </div><!--courseDecriptionBlackout ends-->

<?php echo $footer; ?>
</body>
</html>