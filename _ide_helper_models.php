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
 * @property int $user_id
 * @property string $name
 * @property string|null $country
 * @property string $payout_method
 * @property string|null $bank_name
 * @property string|null $account_number
 * @property string|null $iban
 * @property string|null $swift
 * @property string|null $wallet_provider
 * @property string|null $wallet_phone
 * @property string|null $platform_wallet_id
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property int $is_favorite
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereIsFavorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary wherePayoutMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary wherePlatformWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereSwift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereWalletPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereWalletProvider($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBeneficiary {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $brand
 * @property string|null $last4
 * @property int|null $exp_month
 * @property int|null $exp_year
 * @property string|null $token
 * @property string|null $bank_name
 * @property string|null $iban
 * @property string|null $account_number
 * @property bool $is_default
 * @property numeric $balance
 * @property string $currency
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereExpMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereExpYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPaymentMethod {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $code
 * @property string $title
 * @property string|null $description
 * @property string $discount_percent
 * @property string $start_date
 * @property string $end_date
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPromotion {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $score
 * @property string $context
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReview {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $beneficiary_id
 * @property string $method
 * @property string|null $source
 * @property string|null $destination
 * @property string $src_currency
 * @property string $dst_currency
 * @property string $amount_src
 * @property string|null $amount_dst
 * @property string $fee
 * @property string $fx_rate
 * @property string $status
 * @property string $reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Beneficiary|null $beneficiary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransferEvent> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\TransferService|null $service
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereAmountDst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereAmountSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereBeneficiaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereDstCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereFxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereSrcCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransfer {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $transfer_id
 * @property string $event
 * @property string|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Transfer $transfer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent whereTransferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransferEvent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $code
 * @property string $name
 * @property string $method
 * @property string $fee_percent
 * @property string $fixed_fee
 * @property string $speed
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereFeePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereFixedFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransferService {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $stripe_customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Beneficiary> $beneficiaries
 * @property-read int|null $beneficiaries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentMethod> $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WalletTransaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\Wallet|null $wallet
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStripeCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $wallet_id
 * @property string $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereWalletId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperWallet {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $sender_id
 * @property int|null $receiver_id
 * @property string $tx_type
 * @property string $amount
 * @property string $status
 * @property string $reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $receiver
 * @property-read \App\Models\User|null $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereTxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperWalletTransaction {}
}

