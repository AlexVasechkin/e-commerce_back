import React from 'react';


const DataTable = ({
  getHead = () => null,
  getBody = () => null
}) => {
  return <div className="table-responsive">
    <table className="table table-borderless">
      <thead>
      <tr>{ getHead() }</tr>
      </thead>
      <tbody>{ getBody() }</tbody>
    </table>
  </div>
};

export default DataTable;
