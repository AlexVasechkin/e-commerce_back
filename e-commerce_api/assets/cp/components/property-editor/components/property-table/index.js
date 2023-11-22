import React from 'react';
import DataTable from '../../../common/data-table';
import PropertyTableRow from './property-table-row';


const PropertyTable = ({
  dataSet = []
}) => {

  const getHead = () => <>
    <td>ID</td>
    <td>Имя</td>
    <td>Категории</td>
    <td>Действия</td>
  </>;

  const wrapRow = row => <PropertyTableRow key={ `p${ row.id }` } row={ row } />;

  const getBody = () => dataSet.map(row => wrapRow(row));

  return <DataTable getHead={ getHead } getBody={ getBody } />
}

export default PropertyTable;
