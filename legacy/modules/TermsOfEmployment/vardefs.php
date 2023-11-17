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
$dictionary['TermsOfEmployment'] = array(
    'audited' => true,
    'fields' => array(
        'term_starting_date' => array(
            'required' => true,
            'name' => 'term_starting_date',
            'vname' => 'LBL_TERM_STARTING_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'validation' => array('type' => 'isbefore', 'compareto' => 'term_ending_date'),
            'vt_readonly' => "callCustomApi('TermsOfEmployment', 'checkIfTermInBetween', {id: \$id})",
        ),
        'term_ending_date' => array(
            'required' => false,
            'name' => 'term_ending_date',
            'vname' => 'LBL_TERM_ENDING_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'vt_validation' => "AEM(callCustomApi('TermsOfEmployment', 'validateTermDates', {id: \$id, contract_id: \$contract_id, date_start: \$term_starting_date, date_end: \$term_ending_date,}),'LBL_TERMS_NOT_ADJECENT')",
            'vt_readonly' => "callCustomApi('TermsOfEmployment', 'checkIfTermInBetween', {id: \$id})",
        ),
        'date_of_signing' => array(
            'required' => false,
            'name' => 'date_of_signing',
            'vname' => 'LBL_DATE_OF_SIGNING',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'contracted_employee' => array(
            'name' => 'contracted_employee',
            'type' => 'enum',
            'source' => 'non-db',
            'vname' => 'LBL_CONTRACTED_EMPLOYEE',
        ),
        'gross' => array(
            'required' => false,
            'name' => 'gross',
            'vname' => 'LBL_GROSS',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 26,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 6,
        ),
        'gross_usdollar' => array(
            'required' => false,
            'name' => 'gross_usdollar',
            'vname' => 'LBL_GROSS_USDOLLAR',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 26,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 6,
        ),
        'currency_id' => array(
            'required' => false,
            'name' => 'currency_id',
            'vname' => 'LBL_CURRENCY',
            'type' => 'currency_id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 36,
            'size' => '20',
            'dbType' => 'id',
            'studio' => 'visible',
            'function' => array(
                'name' => 'getCurrencyDropDown',
                'returns' => 'html',
            ),
        ),
        'net' => array(
            'required' => false,
            'name' => 'net',
            'vname' => 'LBL_NET',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 26,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 6,
        ),
        'net_usdollar' => array(
            'required' => false,
            'name' => 'net_usdollar',
            'vname' => 'LBL_NET_USDOLLAR',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 26,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 6,
        ),
        'employer_cost' => array(
            'required' => false,
            'name' => 'employer_cost',
            'vname' => 'LBL_EMPLOYER_COST',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 26,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 6,
        ),
        'employer_cost_usdollar' => array(
            'required' => false,
            'name' => 'employer_cost_usdollar',
            'vname' => 'LBL_EMPLOYER_COST_USDOLLAR',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 26,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 6,
        ),
        "contracts" => array(
            'name' => 'contracts',
            'type' => 'link',
            'relationship' => 'contracts_termsofemployment',
            'source' => 'non-db',
            'module' => 'Contracts',
            'bean_name' => 'Contracts',
            'vname' => 'LBL_CONTRACTS',
            'id_name' => 'contract_id',
        ),
        "contract_name" => array(
            'name' => 'contract_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_CONTRACT_NAME',
            'save' => true,
            'id_name' => 'contract_id',
            'link' => 'contracts_termsofemployment',
            'module' => 'Contracts',
            'table' => 'contracts',
            'rname' => 'name',
            'required' => true,
            'vt_readonly' => "callCustomApi('TermsOfEmployment', 'checkIfTermInBetween', {id: \$id})",
        ),
        "contract_id" => array(
            'name' => 'contract_id',
            'relationship' => 'contracts_termsofemployment',
            'type' => 'id',
            'vname' => 'LBL_CONTRACT_ID',
        ),
        'documents' => array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'documents_termsofemployment',
            'source' => 'non-db',
            'vname' => 'LBL_DOCUMENTS',
        ),
        "positions" => array(
            'name' => 'positions',
            'type' => 'link',
            'relationship' => 'positions_termsofemployment',
            'source' => 'non-db',
            'vname' => 'LBL_POSITIONS',
            'id_name' => 'position_id',
        ),
        'position_id' => array(
            'name' => 'position_id',
            'relationship' => 'positions_termsofemployment',
            'type' => 'id',
            'vname' => 'LBL_POSITION_ID',
            'label' => 'LBL_POSITION_ID',
            'audited' => true,
            'importable' => true,
            'reportable' => true,
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
        ),
        'position_name' => array(
            'name' => 'position_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_POSITION_NAME',
            'label' => 'LBL_POSITION_NAME',
            'id_name' => 'position_id',
            'link' => 'positions',
            'module' => 'Positions',
            'table' => 'positions',
            'rname' => 'name',
            'importable' => true,
            'reportable' => true,
            'audited' => true,
            'required' => true,
        ),
    ),
    'relationships' => array(
        "contracts_termsofemployment" => array(
            'lhs_module' => 'Contracts',
            'lhs_table' => 'contracts',
            'lhs_key' => 'id',
            'rhs_module' => 'TermsOfEmployment',
            'rhs_table' => 'termsofemployment',
            'rhs_key' => 'contract_id',
            'relationship_type' => 'one-to-many',
        ),
        'positions_termsofemployment' => array(
            'lhs_module' => 'Positions',
            'lhs_table' => 'positions',
            'lhs_key' => 'id',
            'rhs_module' => 'TermsOfEmployment',
            'rhs_table' => 'termsofemployment',
            'rhs_key' => 'position_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('TermsOfEmployment', 'TermsOfEmployment',
    array(
        'basic',
        'assignable',
        'security_groups',
        'employee_related',
    ));
