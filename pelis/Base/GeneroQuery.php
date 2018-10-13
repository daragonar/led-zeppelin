<?php

namespace pelis\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use pelis\Genero as ChildGenero;
use pelis\GeneroQuery as ChildGeneroQuery;
use pelis\Map\GeneroTableMap;

/**
 * Base class that represents a query for the 'genero' table.
 *
 *
 *
 * @method     ChildGeneroQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGeneroQuery orderByNombre($order = Criteria::ASC) Order by the nombre column
 *
 * @method     ChildGeneroQuery groupById() Group by the id column
 * @method     ChildGeneroQuery groupByNombre() Group by the nombre column
 *
 * @method     ChildGeneroQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGeneroQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGeneroQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGeneroQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGeneroQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGeneroQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGeneroQuery leftJoinPeliculaGenero($relationAlias = null) Adds a LEFT JOIN clause to the query using the PeliculaGenero relation
 * @method     ChildGeneroQuery rightJoinPeliculaGenero($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PeliculaGenero relation
 * @method     ChildGeneroQuery innerJoinPeliculaGenero($relationAlias = null) Adds a INNER JOIN clause to the query using the PeliculaGenero relation
 *
 * @method     ChildGeneroQuery joinWithPeliculaGenero($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PeliculaGenero relation
 *
 * @method     ChildGeneroQuery leftJoinWithPeliculaGenero() Adds a LEFT JOIN clause and with to the query using the PeliculaGenero relation
 * @method     ChildGeneroQuery rightJoinWithPeliculaGenero() Adds a RIGHT JOIN clause and with to the query using the PeliculaGenero relation
 * @method     ChildGeneroQuery innerJoinWithPeliculaGenero() Adds a INNER JOIN clause and with to the query using the PeliculaGenero relation
 *
 * @method     \pelis\PeliculaGeneroQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildGenero findOne(ConnectionInterface $con = null) Return the first ChildGenero matching the query
 * @method     ChildGenero findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGenero matching the query, or a new ChildGenero object populated from the query conditions when no match is found
 *
 * @method     ChildGenero findOneById(int $id) Return the first ChildGenero filtered by the id column
 * @method     ChildGenero findOneByNombre(string $nombre) Return the first ChildGenero filtered by the nombre column *

 * @method     ChildGenero requirePk($key, ConnectionInterface $con = null) Return the ChildGenero by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGenero requireOne(ConnectionInterface $con = null) Return the first ChildGenero matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGenero requireOneById(int $id) Return the first ChildGenero filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGenero requireOneByNombre(string $nombre) Return the first ChildGenero filtered by the nombre column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGenero[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGenero objects based on current ModelCriteria
 * @method     ChildGenero[]|ObjectCollection findById(int $id) Return ChildGenero objects filtered by the id column
 * @method     ChildGenero[]|ObjectCollection findByNombre(string $nombre) Return ChildGenero objects filtered by the nombre column
 * @method     ChildGenero[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GeneroQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \pelis\Base\GeneroQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\pelis\\Genero', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGeneroQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGeneroQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGeneroQuery) {
            return $criteria;
        }
        $query = new ChildGeneroQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildGenero|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GeneroTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GeneroTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildGenero A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, nombre FROM genero WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildGenero $obj */
            $obj = new ChildGenero();
            $obj->hydrate($row);
            GeneroTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildGenero|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildGeneroQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GeneroTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGeneroQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GeneroTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGeneroQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GeneroTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GeneroTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GeneroTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the nombre column
     *
     * Example usage:
     * <code>
     * $query->filterByNombre('fooValue');   // WHERE nombre = 'fooValue'
     * $query->filterByNombre('%fooValue%', Criteria::LIKE); // WHERE nombre LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nombre The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGeneroQuery The current query, for fluid interface
     */
    public function filterByNombre($nombre = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nombre)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GeneroTableMap::COL_NOMBRE, $nombre, $comparison);
    }

    /**
     * Filter the query by a related \pelis\PeliculaGenero object
     *
     * @param \pelis\PeliculaGenero|ObjectCollection $peliculaGenero the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGeneroQuery The current query, for fluid interface
     */
    public function filterByPeliculaGenero($peliculaGenero, $comparison = null)
    {
        if ($peliculaGenero instanceof \pelis\PeliculaGenero) {
            return $this
                ->addUsingAlias(GeneroTableMap::COL_ID, $peliculaGenero->getGeneroId(), $comparison);
        } elseif ($peliculaGenero instanceof ObjectCollection) {
            return $this
                ->usePeliculaGeneroQuery()
                ->filterByPrimaryKeys($peliculaGenero->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPeliculaGenero() only accepts arguments of type \pelis\PeliculaGenero or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PeliculaGenero relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGeneroQuery The current query, for fluid interface
     */
    public function joinPeliculaGenero($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PeliculaGenero');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PeliculaGenero');
        }

        return $this;
    }

    /**
     * Use the PeliculaGenero relation PeliculaGenero object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \pelis\PeliculaGeneroQuery A secondary query class using the current class as primary query
     */
    public function usePeliculaGeneroQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPeliculaGenero($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PeliculaGenero', '\pelis\PeliculaGeneroQuery');
    }

    /**
     * Filter the query by a related Pelicula object
     * using the pelicula_genero table as cross reference
     *
     * @param Pelicula $pelicula the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGeneroQuery The current query, for fluid interface
     */
    public function filterByPelicula($pelicula, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePeliculaGeneroQuery()
            ->filterByPelicula($pelicula, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGenero $genero Object to remove from the list of results
     *
     * @return $this|ChildGeneroQuery The current query, for fluid interface
     */
    public function prune($genero = null)
    {
        if ($genero) {
            $this->addUsingAlias(GeneroTableMap::COL_ID, $genero->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the genero table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GeneroTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GeneroTableMap::clearInstancePool();
            GeneroTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GeneroTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GeneroTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            GeneroTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GeneroTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // GeneroQuery
