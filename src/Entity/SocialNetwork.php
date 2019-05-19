<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class SocialNetwork implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    protected $url;
    /**
     * @ORM\Column(type="string", length=15)
     */
    protected $network;

    const NETWORKS = [
        self::NETWORK_DISCORD,
        self::NETWORK_FACEBOOK,
        self::NETWORK_TWITTER,
        self::NETWORK_MASTODON,
        self::NETWORK_LINKEDIN,
        self::NETWORK_INSTAGRAM,
        self::NETWORK_GITHUB,
        self::NETWORK_GITLAB
    ];

    const NETWORK_DISCORD = 'discord';
    const NETWORK_FACEBOOK = 'facebook';
    const NETWORK_TWITTER = 'twitter';
    const NETWORK_MASTODON = 'mastodon';
    const NETWORK_LINKEDIN = 'linkedin';
    const NETWORK_INSTAGRAM = 'instagram';
    const NETWORK_GITHUB = 'github';
    const NETWORK_GITLAB = 'gitlab';

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setNetwork(string $network): self
    {
        $this->network = $network;

        return $this;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function jsonSerialize()
    {
        return [
            'url' => $this->url,
            'network' => $this->network
        ];
    }
}