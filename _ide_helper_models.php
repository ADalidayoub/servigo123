<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $provider_id
 * @property string $image
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $provider
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereUpdatedAt($value)
 */
	class Ad extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $provider_id
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Provider|null $provider
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereUpdatedAt($value)
 */
	class Certificate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property int $participant_one
 * @property int $participant_two
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User|null $participantOne
 * @property-read \App\Models\User|null $participantTwo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereParticipantOne($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereParticipantTwo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUpdatedAt($value)
 */
	class Chat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rating_id
 * @property int $provider_id
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $provider
 * @property-read \App\Models\Rating $rating
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport whereRatingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReport whereUpdatedAt($value)
 */
	class CommentReport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $provider_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property-read \App\Models\User|null $provider
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUserId($value)
 */
	class Complaint extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $provider_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $provider
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favourite whereUserId($value)
 */
	class Favourite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $chat_id
 * @property int $sender_id
 * @property string|null $content
 * @property string|null $image_url
 * @property string|null $video_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Chat $chat
 * @property-read \App\Models\User|null $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message withoutTrashed()
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $provider_id
 * @property string $day
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Provider|null $provider
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OffDay whereUpdatedAt($value)
 */
	class OffDay extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string $code
 * @property string $type
 * @property string $expires_at
 * @property int $attempts
 * @property int $is_verified
 * @property string|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtpVerification withoutTrashed()
 */
	class OtpVerification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string $data
 * @property string $role
 * @property string $otp_code
 * @property string $otp_expires_at
 * @property int $otp_attempts
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereOtpAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereOtpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingRegistration withoutTrashed()
 */
	class PendingRegistration extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $provider_id
 * @property string $file_path
 * @property string $file_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Provider|null $provider
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereUpdatedAt($value)
 */
	class Portfolio extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $location_name
 * @property numeric $latitude
 * @property numeric $longitude
 * @property string $work_type
 * @property int $main_service_id
 * @property string|null $id_photo_front
 * @property string|null $id_photo_back
 * @property string $status
 * @property string|null $rejection_reason
 * @property bool $profile_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $sub_service_id
 * @property string|null $location_description
 * @property string|null $currency
 * @property numeric|null $min_price
 * @property numeric|null $max_price
 * @property string|null $work_start_time
 * @property string|null $work_end_time
 * @property bool $overnight
 * @property string|null $about_me
 * @property string|null $off_days
 * @property int $is_available
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Certificate> $certificates
 * @property-read int|null $certificates_count
 * @property-read float $avg_rating
 * @property-read bool $is_available_now
 * @property-read array $off_days_array
 * @property-read string|null $photo
 * @property-read int|null $ratings_count
 * @property-read \App\Models\Service|null $mainService
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OffDay> $offDays
 * @property-read int|null $off_days_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio> $portfolio
 * @property-read int|null $portfolio_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rating> $ratings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SearchLog> $searchLogs
 * @property-read int|null $search_logs_count
 * @property-read \App\Models\SubService|null $subService
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider availableNow()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider minRating($rating)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider notBanned()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider orderByDistance($latitude, $longitude, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider orderByPrice($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider orderByRating($direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider priceRange($min = null, $max = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereAboutMe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereIdPhotoBack($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereIdPhotoFront($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereLocationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereMainServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereMaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereOffDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereOvernight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereProfileCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereSubServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereWorkEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereWorkStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereWorkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider workType($type)
 */
	class Provider extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $provider_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $review
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Provider|null $provider
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereUserId($value)
 */
	class Rating extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $expires_at
 * @property int $revoked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefreshToken whereUserId($value)
 */
	class RefreshToken extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $main_service_id
 * @property int $sub_service_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Service|null $mainService
 * @property-read \App\Models\SubService $subService
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog whereMainServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog whereSubServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchLog whereUserId($value)
 */
	class SearchLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name_ar
 * @property string $name_en
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Provider> $providers
 * @property-read int|null $providers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubService> $subServices
 * @property-read int|null $sub_services_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withoutTrashed()
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $service_id
 * @property string $name_ar
 * @property string $name_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Provider> $providers
 * @property-read int|null $providers_count
 * @property-read \App\Models\Service|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubService whereUpdatedAt($value)
 */
	class SubService extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property string $role
 * @property int $is_banned
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $photo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $chatsAsParticipantOne
 * @property-read int|null $chats_as_participant_one_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $chatsAsParticipantTwo
 * @property-read int|null $chats_as_participant_two_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Provider|null $provider
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

