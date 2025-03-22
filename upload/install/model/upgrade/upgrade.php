<?php
namespace Opencart\Install\Model\Upgrade;
/**
 * Class Install
 *
 * @example $install_model = $this->model_install_install;
 *
 * Can be called from $this->load->model('install/install');
 *
 * @package Opencart\Install\Model\Install
 */
class Upgrade extends \Opencart\System\Engine\Model {
	public function addRecord($table, $data): void {
		$implode = [];

		foreach ($data as $key => $value) {
			$key = $this->db->escape((string)$key);

			switch (gettype($value)) {
				case 'boolean':
					$implode[] = "`" . $key . "` = '" . (bool)$value . "'";
					break;
				case 'integer':
					$implode[] = "`" . $key . "` = '" . (int)$value . "'";
					break;
				case 'double':
					$implode[] = "`" . $key . "` = '" . (float)$value . "'";
					break;
				case 'string':
					$implode[] = "`" . $key . "` = '" . $this->db->escape((string)$value) . "'";
					break;
				case 'array':
					$implode[] = "`" . $key . "` = '" . $this->db->escape((array)json_encode($value)) . "'";
					break;
				case 'object':
					$implode[] = "`" . $key . "` = '" . $this->db->escape((array)json_encode($value)) . "'";
					break;
			}
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . $table . "` SET " . implode(", ", $implode));
	}

	public function getRecords(string $table): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $table . "`");

		return $query->rows;
	}

	public function hasTable(string $table): bool {
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "'");

		return $query->num_rows;
	}

	public function hasField($table, $field): bool {
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "' AND COLUMN_NAME = '" . $field . "'");

		return $query->num_rows;
	}

	public function dropTable($table): void {
		if ($this->hasTable($table)) {
			$this->db->query("DROP TABLE `" . DB_PREFIX . $table . "`");
		}
	}

	public function dropField($table, $field): void {
		if ($this->hasField($table, $field)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . $table . "` DROP `" . $field . "`");
		}
	}
}
