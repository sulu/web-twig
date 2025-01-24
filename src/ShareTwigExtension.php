<?php

namespace Massive\Component\Web;

/**
 * Share twig extension.
 */
class ShareTwigExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $facebookAppId;

    /**
     * @var string
     */
    private $campaignSource;

    /**
     * @param string $facebookAppId
     * @param string $campaignSource
     */
    public function __construct($campaignSource = 'website', $facebookAppId = '')
    {
        $this->campaignSource = $campaignSource;
        $this->facebookAppId = $facebookAppId;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('share_url', [$this, 'getCampaignUrl']),
            new \Twig_SimpleFunction('share_email', [$this, 'getEmailUrl']),
            new \Twig_SimpleFunction('share_pinterest', [$this, 'getPinterestUrl']),
            new \Twig_SimpleFunction('share_facebook', [$this, 'getFacebookUrl']),
            new \Twig_SimpleFunction('share_twitter', [$this, 'getTwitterUrl']),
            new \Twig_SimpleFunction('share_whatsapp', [$this, 'getWhatsAppUrl']),
            new \Twig_SimpleFunction('share_messenger', [$this, 'getMessengerAppUrl']),
        ];
    }

    /**
     * Get email url.
     *
     * @param string $shareUrl
     * @param string $subject
     * @param string $body
     *
     * @return string
     */
    public function getEmailUrl($shareUrl, $subject, $body)
    {
        $shareUrl = $this->getCampaignUrl($shareUrl, 'mail', 'button');

        if (false === strpos($body, '%url%')) {
            $body .= "\r\n\r\n%url%";
        }

        $body = str_replace('%url%', $shareUrl, $body);

        return 'mailto:?subject=' . rawurlencode($subject) . '&body=' . rawurlencode($body);
    }

    /**
     * Get Pinterest url.
     *
     * @param string $shareUrl
     * @param string $media
     * @param string $description
     *
     * @return string
     */
    public function getPinterestUrl($shareUrl, $media, $description)
    {
        $shareUrl = $this->getCampaignUrl($shareUrl, 'pinterest', 'button', true);

        return 'https://pinterest.com/pin/create/button/?url=' . $shareUrl .
            '&media=' . rawurlencode($media) .
            '&description=' . rawurlencode($description);
    }

    /**
     * Get Facebook url.
     *
     * @param string $shareUrl
     *
     * @return string
     */
    public function getFacebookUrl($shareUrl)
    {
        $shareUrl = $this->getCampaignUrl($shareUrl, 'facebook', 'button', true);

        return 'https://www.facebook.com/sharer/sharer.php?u=' . $shareUrl;
    }

    /**
     * Get Twitter url.
     *
     * @param string $shareUrl
     * @param string $title
     *
     * @return string
     */
    public function getTwitterUrl($shareUrl, $title)
    {
        $shareUrl = $this->getCampaignUrl($shareUrl, 'twitter', 'button', true);

        return 'https://www.twitter.com?status=' . rawurlencode($title) . ' ' . $shareUrl;
    }

    /**
     * Get WhatsApp url.
     *
     * @param string $shareUrl
     *
     * @return string
     */
    public function getWhatsAppUrl($shareUrl)
    {
        $shareUrl = $this->getCampaignUrl($shareUrl, 'whatsapp', 'button', true);

        return 'whatsapp://send?text=' . $shareUrl;
    }

    /**
     * Get Messenger url.
     *
     * @param string $shareUrl
     *
     * @return string
     */
    public function getMessengerAppUrl($shareUrl)
    {
        $shareUrl = $this->getCampaignUrl($shareUrl, 'messenger', 'button', true);

        return 'fb-messenger://share/?link=' . $shareUrl . '&app_id=' . $this->facebookAppId;
    }

    /**
     * Get campaign url.
     *
     * @param string $shareUrl
     * @param string $campaign
     * @param string $medium
     * @param bool $encode
     *
     * @return string
     */
    public function getCampaignUrl($shareUrl, $campaign, $medium, $encode = false)
    {
        $delimiter = '?';

        if (strpos($shareUrl, '?')) {
            $delimiter = '&';
        }

        $shareUrl .= $delimiter . 'utm_source=' . $this->campaignSource
            . '&utm_campaign=' . $campaign
            . '&utm_medium=' . $medium;

        if (!$encode) {
            return $shareUrl;
        }

        return rawurlencode($shareUrl);
    }
}
