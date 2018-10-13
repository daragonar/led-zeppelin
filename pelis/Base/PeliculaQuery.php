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
use pelis\Pelicula as ChildPelicula;
use pelis\PeliculaQuery as ChildPeliculaQuery;
use pelis\Map\PeliculaTableMap;

/**
 * Base class that represents a query for the 'pelicula' table.
 *
 *
 *
 * @method     ChildPeliculaQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPeliculaQuery orderByTitulo($order = Criteria::ASC) Order by the titulo column
 * @method     ChildPeliculaQuery orderByAno($order = Criteria::ASC) Order by the ano column
 * @method     ChildPeliculaQuery orderBySinopsis($order = Criteria::ASC) Order by the sinopsis column
 * @method     ChildPeliculaQuery orderByTrailer($order = Criteria::ASC) Order by the trailer column
 *
 * @method     ChildPeliculaQuery groupById() Group by the id column
 * @method     ChildPeliculaQuery groupByTitulo() Group by the titulo column
 * @method     ChildPeliculaQuery groupByAno() Group by the ano column
 * @method     ChildPeliculaQuery groupBySinopsis() Group by the sinopsis column
 * @method     ChildPeliculaQuery groupByTrailer() Group by the trailer column
 *
 * @method     ChildPeliculaQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPeliculaQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPeliculaQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPeliculaQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPeliculaQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPeliculaQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPeliculaQuery leftJoinPeliculaGenero($relationAlias = null) Adds a LEFT JOIN clause to the query using the PeliculaGenero relation
 * @method     ChildPeliculaQuery rightJoinPeliculaGenero($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PeliculaGenero relation
 * @method     ChildPeliculaQuery innerJoinPeliculaGenero($relationAlias = null) Adds a INNER JOIN clause to the query using the PeliculaGenero relation
 *
 * @method     ChildPeliculaQuery joinWithPeliculaGenero($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PeliculaGenero relation
 *
 * @method     ChildPeliculaQuery leftJoinWithPeliculaGenero() Adds a LEFT JOIN clause and with to the query using the PeliculaGenero relation
 * @method     ChildPeliculaQuery rightJoinWithPeliculaGenero() Adds a RIGHT JOIN clause and with to the query using the PeliculaGenero relation
 * @method     ChildPeliculaQuery innerJoinWithPeliculaGenero() Adds a INNER JOIN clause and with to the query using the PeliculaGenero relation
 *
 * @method     ChildPeliculaQuery leftJoinPeliculaProductor($relationAlias = null) Adds a LEFT JOIN clause to the query using the PeliculaProductor relation
 * @method     ChildPeliculaQuery rightJoinPeliculaProductor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PeliculaProductor relation
 * @method     ChildPeliculaQuery innerJoinPeliculaProductor($relationAlias = null) Adds a INNER JOIN clause to the query using the PeliculaProductor relation
 *
 * @method     ChildPeliculaQuery joinWithPeliculaProductor($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PeliculaProductor relation
 *
 * @method     ChildPeliculaQuery leftJoinWithPeliculaProductor() Adds a LEFT JOIN clause and with to the query using the PeliculaProductor relation
 * @method     ChildPeliculaQuery rightJoinWithPeliculaProductor() Adds a RIGHT JOIN clause and with to the query using the PeliculaProductor relation
 * @method     ChildPeliculaQuery innerJoinWithPeliculaProductor() Adds a INNER JOIN clause and with to the query using the PeliculaProductor relation
 *
 * @method     ChildPeliculaQuery leftJoinPeliculaActor($relationAlias = null) Adds a LEFT JOIN clause to the query using the PeliculaActor relation
 * @method     ChildPeliculaQuery rightJoinPeliculaActor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PeliculaActor relation
 * @method     ChildPeliculaQuery innerJoinPeliculaActor($relationAlias = null) Adds a INNER JOIN clause to the query using the PeliculaActor relation
 *
 * @method     ChildPeliculaQuery joinWithPeliculaActor($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PeliculaActor relation
 *
 * @method     ChildPeliculaQuery leftJoinWithPeliculaActor() Adds a LEFT JOIN clause and with to the query using the PeliculaActor relation
 * @method     ChildPeliculaQuery rightJoinWithPeliculaActor() Adds a RIGHT JOIN clause and with to the query using the PeliculaActor relation
 * @method     ChildPeliculaQuery innerJoinWithPeliculaActor() Adds a INNER JOIN clause and with to the query using the PeliculaActor relation
 *
 * @method     \pelis\PeliculaGeneroQuery|\pelis\PeliculaProductorQuery|\pelis\PeliculaActorQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPelicula findOne(ConnectionInterface $con = null) Return the first ChildPelicula matching the query
 * @method     ChildPelicula findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPelicula matching the query, or a new ChildPelicula object populated from the query conditions when no match is found
 *
 * @method     ChildPelicula findOneById(int $id) Return the first ChildPelicula filtered by the id column
 * @method     ChildPelicula findOneByTitulo(string $titulo) Return the first ChildPelicula filtered by the titulo column
 * @method     ChildPelicula findOneByAno(string $ano) Return the first ChildPelicula filtered by the ano column
 * @method     ChildPelicula findOneBySinopsis(string $sinopsis) Return the first ChildPelicula filtered by the sinopsis column
 * @method     ChildPelicula findOneByTrailer(string $trailer) Return the first ChildPelicula filtered by the trailer column *

 * @method     ChildPelicula requirePk($key, ConnectionInterface $con = null) Return the ChildPelicula by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPelicula requireOne(ConnectionInterface $con = null) Return the first ChildPelicula matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPelicula requireOneById(int $id) Return the first ChildPelicula filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPelicula requireOneByTitulo(string $titulo) Return the first ChildPelicula filtered by the titulo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPelicula requireOneByAno(string $ano) Return the first ChildPelicula filtered by the ano column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPelicula requireOneBySinopsis(string $sinopsis) Return the first ChildPelicula filtered by the sinopsis column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPelicula requireOneByTrailer(string $trailer) Return the first ChildPelicula filtered by the trailer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPelicula[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPelicula objects based on current ModelCriteria
 * @method     ChildPelicula[]|ObjectCollection findById(int $id) Return ChildPelicula objects filtered by the id column
 * @method     ChildPelicula[]|ObjectCollection findByTitulo(string $titulo) Return ChildPelicula objects filtered by the titulo column
 * @method     ChildPelicula[]|ObjectCollection findByAno(string $ano) Return ChildPelicula objects filtered by the ano column
 * @method     ChildPelicula[]|ObjectCollection findBySinopsis(string $sinopsis) Return ChildPelicula objects filtered by the sinopsis column
 * @method     ChildPelicula[]|ObjectCollection findByTrailer(string $trailer) Return ChildPelicula objects filtered by the trailer column
 * @method     ChildPelicula[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PeliculaQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \pelis\Base\PeliculaQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\pelis\\Pelicula', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPeliculaQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPeliculaQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPeliculaQuery) {
            return $criteria;
        }
        $query = new ChildPeliculaQuery();
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
     * @return ChildPelicula|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PeliculaTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PeliculaTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPelicula A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, titulo, ano, sinopsis, trailer FROM pelicula WHERE id = :p0';
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
            /** @var ChildPelicula $obj */
            $obj = new ChildPelicula();
            $obj->hydrate($row);
            PeliculaTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPelicula|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PeliculaTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PeliculaTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PeliculaTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PeliculaTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the titulo column
     *
     * Example usage:
     * <code>
     * $query->filterByTitulo('fooValue');   // WHERE titulo = 'fooValue'
     * $query->filterByTitulo('%fooValue%', Criteria::LIKE); // WHERE titulo LIKE '%fooValue%'
     * </code>
     *
     * @param     string $titulo The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByTitulo($titulo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titulo)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaTableMap::COL_TITULO, $titulo, $comparison);
    }

    /**
     * Filter the query on the ano column
     *
     * Example usage:
     * <code>
     * $query->filterByAno('2011-03-14'); // WHERE ano = '2011-03-14'
     * $query->filterByAno('now'); // WHERE ano = '2011-03-14'
     * $query->filterByAno(array('max' => 'yesterday')); // WHERE ano > '2011-03-13'
     * </code>
     *
     * @param     mixed $ano The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByAno($ano = null, $comparison = null)
    {
        if (is_array($ano)) {
            $useMinMax = false;
            if (isset($ano['min'])) {
                $this->addUsingAlias(PeliculaTableMap::COL_ANO, $ano['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ano['max'])) {
                $this->addUsingAlias(PeliculaTableMap::COL_ANO, $ano['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaTableMap::COL_ANO, $ano, $comparison);
    }

    /**
     * Filter the query on the sinopsis column
     *
     * Example usage:
     * <code>
     * $query->filterBySinopsis('fooValue');   // WHERE sinopsis = 'fooValue'
     * $query->filterBySinopsis('%fooValue%', Criteria::LIKE); // WHERE sinopsis LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sinopsis The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterBySinopsis($sinopsis = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sinopsis)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaTableMap::COL_SINOPSIS, $sinopsis, $comparison);
    }

    /**
     * Filter the query on the trailer column
     *
     * Example usage:
     * <code>
     * $query->filterByTrailer('fooValue');   // WHERE trailer = 'fooValue'
     * $query->filterByTrailer('%fooValue%', Criteria::LIKE); // WHERE trailer LIKE '%fooValue%'
     * </code>
     *
     * @param     string $trailer The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByTrailer($trailer = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($trailer)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PeliculaTableMap::COL_TRAILER, $trailer, $comparison);
    }

    /**
     * Filter the query by a related \pelis\PeliculaGenero object
     *
     * @param \pelis\PeliculaGenero|ObjectCollection $peliculaGenero the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByPeliculaGenero($peliculaGenero, $comparison = null)
    {
        if ($peliculaGenero instanceof \pelis\PeliculaGenero) {
            return $this
                ->addUsingAlias(PeliculaTableMap::COL_ID, $peliculaGenero->getPeliculaId(), $comparison);
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
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
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
     * Filter the query by a related \pelis\PeliculaProductor object
     *
     * @param \pelis\PeliculaProductor|ObjectCollection $peliculaProductor the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByPeliculaProductor($peliculaProductor, $comparison = null)
    {
        if ($peliculaProductor instanceof \pelis\PeliculaProductor) {
            return $this
                ->addUsingAlias(PeliculaTableMap::COL_ID, $peliculaProductor->getPeliculaId(), $comparison);
        } elseif ($peliculaProductor instanceof ObjectCollection) {
            return $this
                ->usePeliculaProductorQuery()
                ->filterByPrimaryKeys($peliculaProductor->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPeliculaProductor() only accepts arguments of type \pelis\PeliculaProductor or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PeliculaProductor relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function joinPeliculaProductor($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PeliculaProductor');

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
            $this->addJoinObject($join, 'PeliculaProductor');
        }

        return $this;
    }

    /**
     * Use the PeliculaProductor relation PeliculaProductor object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \pelis\PeliculaProductorQuery A secondary query class using the current class as primary query
     */
    public function usePeliculaProductorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPeliculaProductor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PeliculaProductor', '\pelis\PeliculaProductorQuery');
    }

    /**
     * Filter the query by a related \pelis\PeliculaActor object
     *
     * @param \pelis\PeliculaActor|ObjectCollection $peliculaActor the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByPeliculaActor($peliculaActor, $comparison = null)
    {
        if ($peliculaActor instanceof \pelis\PeliculaActor) {
            return $this
                ->addUsingAlias(PeliculaTableMap::COL_ID, $peliculaActor->getPeliculaId(), $comparison);
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
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
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
     * Filter the query by a related Genero object
     * using the pelicula_genero table as cross reference
     *
     * @param Genero $genero the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByGenero($genero, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePeliculaGeneroQuery()
            ->filterByGenero($genero, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Productor object
     * using the pelicula_productor table as cross reference
     *
     * @param Productor $productor the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByProductor($productor, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePeliculaProductorQuery()
            ->filterByProductor($productor, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Actor object
     * using the pelicula_actor table as cross reference
     *
     * @param Actor $actor the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPeliculaQuery The current query, for fluid interface
     */
    public function filterByActor($actor, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePeliculaActorQuery()
            ->filterByActor($actor, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPelicula $pelicula Object to remove from the list of results
     *
     * @return $this|ChildPeliculaQuery The current query, for fluid interface
     */
    public function prune($pelicula = null)
    {
        if ($pelicula) {
            $this->addUsingAlias(PeliculaTableMap::COL_ID, $pelicula->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pelicula table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PeliculaTableMap::clearInstancePool();
            PeliculaTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PeliculaTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PeliculaTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PeliculaTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PeliculaQuery
