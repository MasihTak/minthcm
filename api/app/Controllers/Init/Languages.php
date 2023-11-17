<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Controllers\Init;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class Languages
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        global $sugar_config;

        $response = $response->withHeader('Content-type', 'application/json');

        $modules = $request->getAttribute('modules') ?? array();
        $lang = $request->getAttribute('lang') ?? $_SESSION["authenticated_user_language"] ?? $sugar_config['default_language'];
        $response_body = $this->getLanguages($modules, $lang);

        $response->getBody()->write(json_encode($response_body));
        return $response;
    }

    public function getLanguages(array $modules = [], string $lang = null): array
    {
        global $sugar_config, $app_list_strings, $app_strings, $current_language;
        $current_language = $lang ?? $_SESSION["authenticated_user_language"] ?? $sugar_config['default_language'];

        $response = array();
        chdir('../legacy/');
        $app_list_strings = return_app_list_strings_language($current_language);
        $app_strings = return_application_language($current_language);
        $response['app_list_strings'] = $app_list_strings;
        $response['app_strings'] = $app_strings;
        foreach ($modules as $module) {
            $response[$module] = return_module_language($current_language, $module);
        }
        chdir('../api/');
        return $response;
    }
}
