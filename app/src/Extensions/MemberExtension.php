<?php

use SilverStripe\ORM\DataExtension;

class MemberExtension extends DataExtension
{
    private static $table_name = "member_extension";
    private static $db = [
        'VerificationToken' => 'Varchar(255)',
        'IsVerified' => 'Boolean',
        'ResetPasswordToken' => 'Varchar(255)',
        'ResetPasswordExpiry' => 'Datetime',
        'GoogleID' => 'Varchar(255)',
        'TotalTransactions' => 'Double',
        'MembershipTier' => 'Int',
        'MembershipTierName' => 'Varchar(100)',
        'MembershipPeriodeStart' => 'Datetime',
        'LastMembershipUpdate' => 'Datetime',
        'PopupViewCount' => 'Int',
        'LastPopupDate' => 'Date'
    ];

    private static $indexes = [
        'GoogleID' => true,
        'VerificationToken' => true,
        'ResetPasswordToken' => true,
    ];

    public function updateSummaryFields(&$fields)
    {
        $fields['GoogleID'] = 'GoogleID';
        $fields['IsVerified'] = 'Terverifikasi';
        $fields['MembershipTierName'] = 'Membership Tier';
        $fields['FormattedTotalTransactions'] = 'Total Transaksi';
        $fields['MembershipPeriodStart'] = 'Periode Mulai';
        $fields['LastMembershipUpdate'] = 'Terakhir Update';
        $fields['PopupViewCount'] = 'Pop-up count';
        $fields['LastPopupDate'] = 'Terakhir Pop-up Update';
    }

    public function getFormattedTotalTransactions()
    {
        return 'Rp ' . number_format($this->owner->TotalTransactions, 0, '.', '.');
    }

    public function getMembershipTierName()
    {
        return MembershipService::getMembershipTierName($this->owner->MembershipTier);
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->owner->MembershipPeriodeStart) {
            $this->owner->MembershipPeriodeStart = date('Y-m-d H:i:s');
        }
    }
}