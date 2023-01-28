<?php
	function VisitorIP() { 
    	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        	$TheIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
    	else $TheIp=$_SERVER['REMOTE_ADDR'];
    	return trim($TheIp);
    	}
	
// email validator...	
	function check_email_address($email) {
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
         if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }    
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
	}	
?>
<!DOCTYPE html>
<html>
<head>
  <title>Law Office of Karen Lee Dunne, Esq. | New York Estate Attorney, Estate Law Office: Suffolk, Nassau, Queens, Brooklyn, Manhatten, Bronx, Staten Island.</title>
  <meta name="description" content="Law Office of Karen Lee Dunne, Esq., New York. Estate Planning, Probate, Wills and Trusts. Serving New York including Brooklyn, Queens and Manhattan. Long Island, NY The counties of Nassau, Suffolk, Queens and Brooklyn.">
  <meta name="keywords" content="estate attorney, long island, new york, lawyer, probate, legal, attorney, law firm, counsel, law office, law, planning, legal professional, arbitration, claim, compensation, mediation">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="stf/sty.css" type="text/css">
  <script language="JavaScript" src="stf/func.js" type="text/javascript"></script>
  <link rel="shortcut icon" href="favicon.ico">
</head>
<body class="contact">
    <div id="head">
        <h1>Law Offices of Karen L. Dunne <span><a href="http://www.martindale.com/Karen-L-Dunne/392616-lawyer.htm" target="_blank">Martindale-Hubbell AV Rated</a></span></h1>
    </div>
    <div id="splash">
        <div id="nav-btn" onclick="mobile_menu()"></div>
        <div id="nav">
            <a href="/">Home</a>
            <a href="practice.html">Practice</a>
            <a href="bio.html">Biography</a>
            <a href="community.html">Community</a>
            <a href="contact.php" class="hi">Contact</a>
        </div>
        <div id="splashimg"><img src="images/kldesq.jpg"><img src="images/riverhead_court.jpg"><img src="images/gavel_flag.jpg" alt=""><a title="AV Preeminent Martindale-Hubbell Laywer Rating" target="_blank" href="http://www.martindale.com/Karen-L-Dunne/392616-lawyer.htm"><img alt="AV Preeminent Martindale-Hubbell Laywer Rating" src="images/av_card.jpg" border="0" /></a><!-- <a target="_blank" href="https://www.youtube.com/watch?v=rROrAyiJABo" title="AV Preeminent Martindale-Hubbell Laywer Rating"><img src="http://www.americanregistry.com/previewlarge.jpg" height="187" border="0" alt="AV Preeminent Martindale-Hubbell Laywer Rating" /></a> --></div>
    </div>
    <div id="main">
        <div id="col1">
         <?php
            if ($_POST && $_POST['fname'] != '' && $_POST['subject'] != '' && $_POST['email'] != '' && $_POST['message'] != '' && check_email_address($_POST['email'])) { 
                // set variables using data 
                $ip = VisitorIP();
                $now = date('m-d-Y h:i:sa');		
                $fname = $_POST['fname'];
                $email = $_POST['email'];
                $subject = $_POST['subject'];
                $message = $_POST['message'];
                $second_name = $_POST['s_name'];
                $stamp = date('Y-m-d H:i:s');
                $resend = date('Y-m-d H:i:s', strtotime($stamp . '+ 14 days'));
                
                if ($second_name != ''){   // this is a bot.
                    echo 'Your submission has been flagged as spam, if this was a mistake please e-mail Karen L Dunne Esq directly.  Thanks!';

                } else { 
                        include_once('class.phpmailer.php');

                        $mail = new PHPMailer();
                        $mail->PluginDir = '';
                        $mail->Encoding="base64";

                        $mail->AddAddress('karenldunneesq@aol.com'); 
                        $mail->AddBCC('bove2k@yahoo.com');
                        
                        $mail->Subject = 'Karen L Dunne Esq Website - ' . $subject;
                        $mail->SetFrom($email);
                        $mail->AddReplyTo(strip_tags($email), strip_tags($fname) );

                        $html = '<html><body>';
                        $html .= '<table border="0" width="444"><tr><td>'; 
                        $html .= '<font size="3" color="#776D65" face="Tahoma, Arial, sans-serif">'; 
                        $html .= '<p><br>An Email has been sent from the Karen L Dunne Esq website:</p>';
                        $html .= '<p><u>Name:</u> ' . $fname . '<br>';
                        $html .= '<p><u>Email:</u> ' . $email . '<br>';
                        $html .= '<p><u>Subject:</u> ' . $subject . '<br>';
                        $html .= '<p><u>Message:</u> ' . $message . '<br>';
                        $html .= '</font></td></tr></table></body></html>';

                        $mail->AltBody = 'Name: ' . strip_tags($fname) . ' Email:' . strip_tags($email) . ' Subject:' . strip_tags($subject) . ' Memo:' . strip_tags($message);

                        $mail->MsgHTML($html);  
                
                        if( $mail->Send()){
                            echo '<form id="contest_form">';				  
                            echo '  <h2><br>' . $fname . ' (' . $email . '), your message has been sent.  Thank you for your submission!</h2>';
                            echo '  <p><br><a href="contact.php">Reset Form to send another..</a></p>'; 
                            echo '</form>';
                        } else {
                            echo '<form id="contest_form"><h3>Email failed.</h3></form>'; 
                        }

                } // end if - bot test

            } else { 			
         ?>        
            <form method="post" id="contest_form" enctype="multipart/form-data" action="contact.php?f=s">
                    <?php if ($_GET['f'] == 's'){ echo "<span style='font-size:75%;display:block;color:red;'>Your entry could not be submitted because some fields are missing...<br><br></span>"; } ?>
                    
                <label class="col" for="rtype" <?php if ($_GET['f'] == 's' && $_POST['fname'] == ''){ echo "class='error'"; } ?>>

                <label for="fname" <?php if ($_GET['f'] == 's' && $_POST['fname'] == ''){ echo "class='error'"; } ?>> 
                                Name: <input type="text" name="fname" id="fname" placeholder="Name" value="<?php if ($_GET['f'] == 's'){ echo $_POST['fname']; } ?>"></label>           
                                
                <input type="hidden" id="s_name"> 
                <label for="email" <?php if ( $_GET['f'] == 's' && ($_POST['email'] == '' || !check_email_address($email)) ){ echo "class='error'"; } ?>> 
                                Email: <input type="text" name="email" id="email" placeholder="Contact Email" value="<?php if ($_GET['f'] == 's'){ echo $_POST['email']; } ?>"></label>
                        
                <label for="subject" <?php if ($_GET['f'] == 's' && $_POST['subject'] == ''){ echo "class='error'"; } ?>> 
                                Subject: <input type="text" name="subject" id="subject" placeholder="Subject" value="<?php if ($_GET['f'] == 's'){ echo $_POST['subject']; } ?>"></label>        
                        
                <label for="message" <?php if ($_GET['f'] == 's' && $_POST['message'] == ''){ echo "class='error'"; } ?>> 
                                Message: <textarea name="message" id="message" placeholder="Message"><?php if ($_GET['f'] == 's'){ echo $_POST['message']; } ?></textarea></label> 
                            
                <input type="submit" name="submiit" value="Submit" onClick="submitit();">
                <span style="font-size:75%;display:block;"><br>* All fields are required</span>
                        
            </form>
            <script>
                function submitit() {
                    document.getElementById("contest_form").submit();
                }
            </script>
         <?php
            }
         ?>

        </div>
        <div id="col2">
            <h1>32 Gateway Lane, Manorville, NY 11949<br>
                <i>ph:</i><a>631-878-4171</a> &bull;<i> fx:</i><a>631-874-5058</a><br>
                <i>email:</i><a href="mailto:karenldunneesq@aol.com">karenldunneesq@aol.com</a></h1>
            <h2>Services - Areas of Practice</h2>
            Estate Administration<br /> Estate Planning<br /> Guardianships<br /> Wrongful Death Proceedings<br /> Personal Injury<br /> Real Estate
            <div id="testi">
            <p>"It was great doing business with you and your firm.  I can't thank you guys enough for handling something so emotional and precious to me.  God bless and stay safe."  &nbsp; &nbsp; &nbsp;  ~ Polly F.<br /></p>
            <p>"Thank   you again for your extreme professionalism.&nbsp; It is greatly   appreciated.&nbsp; You handle your business thoroughly and quickly."&nbsp; &nbsp; &nbsp; &nbsp;  ~ Marianne C.</p>
            </div>
        </div>
        <br class="clear" />
    </div>
    <div id="foot">
        <p><b>Karen L. Dunne - New York Estate Probate Inheritance Attorney:</b><br /> The estate, probate and trust law attorney, New York City, Manhattan, Bronx, Queens, Brooklyn, Nassau, Suffolk, Long Island, Westchester   probate &amp; estate attorney, estate administration &amp; settlement   lawyer, New York inheritance law &amp; inheritance disputes, estate   litigation, contested estates &amp; trusts law office, estate planning   lawyer, estate tax attorney, administering estates and trusts, probating   wills attorney, estate tax returns, estate &amp; trust accountings,   executors &amp; trustees, or other legal information presented at this   site should not be construed to be formal legal advice nor the formation   of a lawyer or attorney client relationship. Any results set forth here   were dependent on the facts of that case and the results will differ   from case to case. Please contact our New York inheritance, probate,   trust and estate law office - New York, NY. Attorney Advertising.</p>
        <p>Copyright &copy; 2013 Law Offices of Karen L. Dunne | Attorney Advertising | <a href="https://bove2k.com">Web Design</a></p>
    </div>
 
</body>
</html>