<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @group Adminstration
 *
 * To manage the controls of admins and moderators
 */

class Administration extends Controller
{

    /**
     * deleteApexCom
     * Deleting the ApexCom by the admin.
     * Success Cases :
     * 1) return true to ensure ApexCom is deleted successfully.
     * failure Cases:
     * 1) Apex fullname (ID) is not found.
     * 2) NoAccessRight the token is not the site admin token id.
     *
     * @bodyParam Apex_ID string required The ID of the ApexCom to be deleted.
     * @bodyParam token JWT required Used to verify the admin ID.
     */

    public function deleteApexCom()
    {
        return;
    }





    /**
     * deleteUser
     * Delete a user from the application by the admin or self-delete (Account deactivation).
     * Success Cases :
     * 1) return true to ensure that the user is deleted successfully.
     * failure Cases:
     * 1) user fullname (ID) is not found.
     * 2) NoAccessRight the token is not the site admin or the same user token id.
     *
     * @bodyParam UserID string required The ID of the user to be deleted.
     * @bodyParam Reason string The reason for deleting the user.
     * @bodyParam token JWT required Used to verify the admin or the same user ID.
     */

    public function deleteUser()
    {
        return;
    }




    /**
     * addModerator
     * Adding (or Deleting) a moderator to ApexCom.
     * Success Cases :
     * 1) return true to ensure that the moderator is added successfully.
     * failure Cases:
     * 1) user fullname (ID) is not found.
     * 2) NoAccessRight the token is not the site admin token id.
     *
     * @bodyParam ApexComID string required The ID of the ApexCom.
     * @bodyParam token JWT required Used to verify the Admin ID.
     * @bodyParam UserID string required The user ID to be added as a moderator.
     */

    public function addModerator()
    {
        return;
    }
}
