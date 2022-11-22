import * as React from 'react';
import Paper from '@mui/material/Paper';
import { BlogProps } from '../Blog';
import PostsTable from '../Component/PostsTable';

export default function AdminPosts(props: BlogProps) {
  return (
    <Paper sx={{ p: 2, display: 'flex', flexDirection: 'column' }}>
      <PostsTable {...props} />
    </Paper>
  );
}