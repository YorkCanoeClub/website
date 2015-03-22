<?php

function template_main() {
    
    global $context;
    
    ?>

<h1>Thankyou for joining the waiting list</h1>
<p>Your unique id on the waiting list is '<b><?php print($context["waitingListId"]); ?></b>'. We'll be in touch when a space becomes available for you to start. Please ensure you keep an eye on your email and whitelist the yorkcanoeclub.co.uk domain so that our emails don't end up in your spambox. You'll also be able to <a href="https://www.yorkcanoeclub.co.uk/index.php?action=ywaiting">track your progress on the website</a></p>
<p>You will also receive confirmation via email to the email address you have provided. If you don't receive this email within 48 hours please get in touch.</p>

<?php } ?>
