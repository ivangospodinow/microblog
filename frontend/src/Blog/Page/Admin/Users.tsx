import * as React from 'react';
import Paper from '@mui/material/Paper';
import { BlogProps } from '../../Blog';
import UsersTable from '../../Component/UsersTable';

export default function Users(props: BlogProps) {
  return (
    <Paper sx={{ p: 2, display: 'flex', flexDirection: 'column' }}>
      <UsersTable {...props} />
    </Paper>
  );
}