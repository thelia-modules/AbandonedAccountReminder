<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace AbandonedAccountReminder\Model;

use AbandonedAccountReminder\Model\Base\AbandonedAccount as BaseAbandonedAccount;

class AbandonedAccount extends BaseAbandonedAccount
{
    public const STATUS_NOT_SENT = 0;
    public const STATUS_SENT = 1;
}
