<?php

/**
 * Wrapper class for sending mails through the PHPMailer library.
 *
 * @author Leo Rudin
 */

namespace App\Libraries;

use App\Accessor;

class Mail extends Accessor
{
    /**
     * Defines from which address the mail should be send from.
     *
     * @var string
     */
    private $from = 'noreply@thvm.ch';

    /**
     * Defines to which address the users answer if they hit the answer-button.
     *
     * @var string
     */
    private $replyTo = 'support@thvm.ch';

    /**
     * Contains array with required data for sending the mail.
     *
     * @var array
     */
    private $data = [];

    /**
     * Assigns required data to the data attribute.
     *
     * @param  $data
     * @return $this
     */
    public function init($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Sends the mail.
     *
     * @return bool
     */
    public function send()
    {
        $this->phpmailer->isSMTP();

        // Authentication
        $this->phpmailer->Port = $this->config->get('mail.port');
        $this->phpmailer->SMTPDebug = 0;
        $this->phpmailer->SMTPSecure = $this->config->get('mail.secure');
        $this->phpmailer->SMTPAuth = true;

        $this->phpmailer->Host = $this->config->get('mail.host');
        $this->phpmailer->Username = $this->config->get('mail.username');
        $this->phpmailer->Password = $this->config->get('mail.password');

        // Settings
        $this->phpmailer->CharSet = 'UTF-8';
        $this->phpmailer->isHTML(true);

        // Content
        $this->phpmailer->setFrom($this->from);
        $this->phpmailer->addReplyTo($this->replyTo);
        $this->phpmailer->addAddress($this->data['to']);
        $this->phpmailer->Subject = $this->data['subject'];

        $this->phpmailer->msgHTML($this->data['content']);
        $this->phpmailer->AltBody = 'Ihr E-Mail Client kann diese E-Mail leider nicht anzeigen. Wir empfehlen Outlook oder Thunderbird.';

        return $this->phpmailer->send();
    }
}