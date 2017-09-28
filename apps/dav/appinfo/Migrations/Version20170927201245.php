<?php
/**
 * @author Victor Dubiniuk <dubiniuk@owncloud.com>
 *
 * @copyright Copyright (c) 2017, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\DAV\Migrations;

use OCP\Migration\ISchemaMigration;
use Doctrine\DBAL\Schema\Schema;

/*
 * Recreate userid and propertypath
 * if they were dropped in 10.0.3
 * See https://github.com/owncloud/core/issues/29047
 */

class Version20170927201245 implements ISchemaMigration {

	/**
	 * @param Schema $schema
	 * @param array $options
	 */
	public function changeSchema(Schema $schema, array $options) {
		$prefix = $options['tablePrefix'];
		$table = $schema->getTable("${prefix}properties");
		if (!$table->hasColumn('userid')) {
			$table->addColumn('userid', 'string', [
				'notnull' => false,
				'length' => 64,
				'default' => '',
			]);
		} else {
			$userIdColumn = $table->getColumn('userid');
			$userIdColumn->setOptions([
					'notnull' => false,
					'length' => 64,
					'default' => '',
			]);
		}

		if (!$table->hasColumn('propertypath')) {
			$table->addColumn('propertypath', 'string', [
				'notnull' => false,
				'length' => 255,
				'default' => '',
			]);
		} else {
			$propertyPathColumn = $table->getColumn('propertypath');
			$propertyPathColumn->setOptions([
				'notnull' => false,
				'length' => 255,
				'default' => '',
			]);
		}

		if (!$table->hasIndex('property_index')) {
			$table->addIndex(['userid'], 'property_index');
		}
	}
}
