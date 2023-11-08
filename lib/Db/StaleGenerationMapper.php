<?php

// SPDX-FileCopyrightText: Sami Finnilä <sami.finnila@nextcloud.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace OCA\Text2ImageHelper\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use RuntimeException;

/**
 * @implements QBMapper<StaleGeneration>
 */
class StaleGenerationMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 't2ih_stale_gens', StaleGeneration::class);
	}

	/**
	 * @param int $id
	 * @return StaleGeneration
	 * @throws DoesNotExistException
	 * @throws Exception
	 * @throws MultipleObjectsReturnedException
	 */
	public function getStaleGeneration(int $id): StaleGeneration {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);

		return $this->findEntity($qb);
	}



	/**
	 * @param string $imageGenId
	 * @return array
	 * @throws Exception
	 */
	public function getStaleGenerationByGenId(string $imageGenId): array {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('image_gen_id', $qb->createNamedParameter($imageGenId, IQueryBuilder::PARAM_STR))
			);

		return $this->findEntities($qb);
	}

	/**
	 * Check if a image_gen id exists in the database table
	 * @param string $imageGenId
	 * @return bool
	 * @throws Exception
	 * @throws RuntimeException
	 */
	public function genIdExists(string $imageGenId): bool {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('image_gen_id', $qb->createNamedParameter($imageGenId, IQueryBuilder::PARAM_STR))
			);

		$result = $qb->executeQuery();
		$row = $result->fetch();
		return $row !== false;
	}

	/**
	 * @param string $imageGenId
	 * @return StaleGeneration
	 * @throws Exception
	 */
	public function createStaleGeneration(string $imageGenId): StaleGeneration {
		$staleGen = new StaleGeneration();
		$staleGen->setImageGenId($imageGenId);

		$insertedStaleGeneration = $this->insert($staleGen);

		return $insertedStaleGeneration;
	}
}
