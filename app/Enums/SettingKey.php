<?php

namespace App\Enums;

enum SettingKey: string
{
    case SeoDescription = 'seo_description';
    case AnalyticsId = 'analytics_id';
    case SocialLinkedin = 'social_linkedin';
    case SocialTwitter = 'social_twitter';
    case SocialGithub = 'social_github';
    case SocialInstagram = 'social_instagram';
    case ContactAddress = 'contact_address';
    case ContactEmail = 'contact_email';
    case ContactPhone = 'contact_phone';
}
