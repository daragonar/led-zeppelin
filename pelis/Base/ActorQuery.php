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
use pelis\Actor as ChildActor;
use pelis\ActorQuery as ChildActorQuery;
use pelis\Map\ActorTableMap;

/**
 * Base class that represents a query for the 'actor' table.
 *
 *
 *
 * @method     ChildActorQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildActorQuery orderByNombre($order = Criteria::ASC) Order by the nombre column
 * @method     ChildActorQuery orderByApellido($order = Criteria::ASC) Order by the apellido column
 * @method     ChildActorQuery orderByEdad($order = Criteria::ASC) Order by the edad column
 *
 * @method     ChildActorQuery groupById() Group by the id column
 * @method     ChildActorQuery groupByNombre() Group by the nombre column
 * @method     ChildActorQuery groupByApellido() Group by the apellido column
 * @method     ChildActorQuery groupByEdad() Group by the edad column
 *
 * @method     ChildActorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildActorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildActorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildActorQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildActorQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildActorQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildActorQuery leftJoinPeliculaActor($relationAlias = null) Adds a LEFT JOIN clause to the query using the PeliculaActor relation
 * @method     ChildActorQuery rightJoinPeliculaActor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PeliculaActor relation
 * @method     ChildActorQuery innerJoinPeliculaActor($relationAlias = null) Adds a INNER JOIN clause to the query using the PeliculaActor relation
 *
 * @method     ChildActorQuery joinWithPeliculaActor($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PeliculaActor relation
 *
 * @method     ChildActorQuery leftJoinWithPeliculaActor() Adds a LEFT JOIN clause and with to the query using the PeliculaActor relation
 * @method     ChildActorQuery rightJoinWithPeliculaActor() Adds a RIGHT JOIN clause and with to the query using the PeliculaActor relation
 * @method     ChildActorQuery innerJoinWithPeliculaActor() Adds a INNER JOIN clause and with to the query using the PeliculaActor relation
 *
 * @method     \pelis\PeliculaActorQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildActor findOne(ConnectionInterface $con = null) Return the first ChildActor matching the query
 * @method     ChildActor findOneOrCreate(ConnectionInterface $con = null) Return the first ChildActor matching the query, or a new ChildActor object populated from the query conditions when no match is found
 *
 * @method     ChildActor findOneById(int $id) Return the first ChildActor filtered by the id column
 * @method     ChildActor findOneByNombre(string $nombre) Return the first ChildActor filtered by the nombre column
 * @method     ChildActor findOneByApellido(string $apellido) Return the first ChildActor filtered by the apellido column
 * @method     ChildActor findOneByEdad(int $edad) Return the first ChildActor filtered by the edad column *

 * @method     ChildActor requirePk($key, ConnectionInterface $con = null) Return the ChildActor by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActor requireOne(ConnectionInterface $con = null) Return the first ChildActor matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActor requireOneById(int $id) Return the first ChildActor filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActor requireOneByNombre(string $nombre) Return the first ChildActor filtered by the nombre column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActor requireOneByApellido(string $apellido) Return the first ChildActor filtered by the apellido column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActor requireOneByEdad(int $edad) Return the first ChildActor filtered by the edad column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActor[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildActor objects based on current ModelCriteria
 * @method     ChildActor[]|ObjectCollection findById(int $id) Return ChildActor objects filtered by the id column
 * @method     ChildActor[]|ObjectCollection findByNombre(string $nombre) Return ChildActor objects filtered by the nombre column
 * @method     ChildActor[]|ObjectCollection findByApellido(string $apellido) Return ChildActor objects filtered by the apellido column
 * @method     ChildActor[]|ObjectCollection findByEdad(int $edad) Return ChildActor objects filtered by the edad column
 * @method     ChildActor[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ActorQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \pelis\Base\ActorQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\pelis\\Actor', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildActorQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildActorQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildActorQuery) {
            return $criteria;
        }
        $query = new ChildActorQuery();
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
     * @return ChildActor|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActorTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ActorTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildActor A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, nombre, apellido, edad FROM actor WHERE id = :p0';
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
            /** @var ChildActor $obj */
            $obj = new ChildActor();
            $obj->hydrate($row);
            ActorTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildActor|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ActorTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ActorTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ActorTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ActorTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActorTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function filterByNombre($nombre = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nombre)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActorTableMap::COL_NOMBRE, $nombre, $comparison);
    }

    /**
     * Filter the query on the apellido column
     *
     * Example usage:
     * <code>
     * $query->filterByApellido('fooValue');   // WHERE apellido = 'fooValue'
     * $query->filterByApellido('%fooValue%', Criteria::LIKE); // WHERE apellido LIKE '%fooValue%'
     * </code>
     *
     * @param     string $apellido The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function filterByApellido($apellido = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($apellido)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActorTableMap::COL_APELLIDO, $apellido, $comparison);
    }

    /**
     * Filter the query on the edad column
     *
     * Example usage:
     * <code>
     * $query->filterByEdad(1234); // WHERE edad = 1234
     * $query->filterByEdad(array(12, 34)); // WHERE edad IN (12, 34)
     * $query->filterByEdad(array('min' => 12)); // WHERE edad > 12
     * </code>
     *
     * @param     mixed $edad The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function filterByEdad($edad = null, $comparison = null)
    {
        if (is_array($edad)) {
            $useMinMax = false;
            if (isset($edad['min'])) {
                $this->addUsingAlias(ActorTableMap::COL_EDAD, $edad['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($edad['max'])) {
                $this->addUsingAlias(ActorTableMap::COL_EDAD, $edad['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActorTableMap::COL_EDAD, $edad, $comparison);
    }

    /**
     * Filter the query by a related \pelis\PeliculaActor object
     *
     * @param \pelis\PeliculaActor|ObjectCollection $peliculaActor the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActorQuery The current query, for fluid interface
     */
    public function filterByPeliculaActor($peliculaActor, $comparison = null)
    {
        if ($peliculaActor instanceof \pelis\PeliculaActor) {
            return $this
                ->addUsingAlias(ActorTableMap::COL_ID, $peliculaActor->getActorId(), $comparison);
        } elseif ($peliculaActor instanceof ObjectCollection) {
            return $this
                ->usePeliculaActorQuery()
                ->filterByPrimaryKeys($peliculaActor->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPeliculaActor() only accepts arguments of type \pelis\PeliculaActor or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PeliculaActor relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function joinPeliculaActor($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PeliculaActor');

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
            $this->addJoinObject($join, 'PeliculaActor');
        }

        return $this;
    }

    /**
     * Use the PeliculaActor relation PeliculaActor object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \pelis\PeliculaActorQuery A secondary query class using the current class as primary query
     */
    public function usePeliculaActorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPeliculaActor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PeliculaActor', '\pelis\PeliculaActorQuery');
    }

    /**
     * Filter the query by a related Pelicula object
     * using the pelicula_actor table as cross reference
     *
     * @param Pelicula $pelicula the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActorQuery The current query, for fluid interface
     */
    public function filterByPelicula($pelicula, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePeliculaActorQuery()
            ->filterByPelicula($pelicula, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildActor $actor Object to remove from the list of results
     *
     * @return $this|ChildActorQuery The current query, for fluid interface
     */
    public function prune($actor = null)
    {
        if ($actor) {
            $this->addUsingAlias(ActorTableMap::COL_ID, $actor->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the actor table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActorTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ActorTableMap::clearInstancePool();
            ActorTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ActorTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ActorTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ActorTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ActorTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ActorQuery
