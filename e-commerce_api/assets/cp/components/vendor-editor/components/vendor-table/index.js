import React from 'react';
import DataTable from '../../../common/data-table';
import VendorTableRow from './vendor-table-row';


const VendorTable = ({
  dataSet = []
}) => {

  const getHead = () => <>
    <td>ID</td>
    <td>Имя</td>
    <td>Действия</td>
  </>;

  const wrapRow = row => <VendorTableRow key={ `vr-${ row.id }` } row={ row } />;

  const getBody = () => dataSet.map(row => wrapRow(row));

  return <DataTable getHead={ getHead } getBody={ getBody } />
};

export default VendorTable;
