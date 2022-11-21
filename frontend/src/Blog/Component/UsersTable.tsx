import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Typography from '@mui/material/Typography';
import { BlogProps } from '../Blog';
import { ADMIN_LIST_RECORDS_COUNT } from '../../config';
import { BlogUser, BlogUsers } from "../../Service/DataService";
import Button from '@mui/material/Button';

import UserForm from "../Form/UserForm";
import AddIcon from '@mui/icons-material/Add';


export default function UsersTable(props: BlogProps) {

  const [showUserForm, setShowUserForm] = useState<boolean>(false);
  const [userToEdit, setUserToEdit] = useState<BlogUser | null>();
  const [users, setUsers] = useState<BlogUsers>([]);
  const [usersLoaded, setUsersLoaded] = useState(false);

  let { page } = useParams<{ page: string }>();
  const currentPage = parseInt(page || '1');

  useEffect(() => {

    (async () => {
      const users = await props.dataService.getUsers({
        list: {
          limit: ADMIN_LIST_RECORDS_COUNT,
          page: currentPage,
        },
      });
      setUsersLoaded(true);
      setUsers(users);
    })();


  }, [usersLoaded, page]);


  return (
    <React.Fragment>
      <Typography component="h2" variant="h6" color="primary" gutterBottom>
        Users
        <Button variant="contained" size="small" onClick={() => {
          setUserToEdit(null);
          setShowUserForm(true);
        }} style={{ float: 'right' }}>
          <AddIcon />
        </Button>
      </Typography>
      <Table size="small">
        <TableHead>
          <TableRow>
            <TableCell>Id</TableCell>
            <TableCell>Username</TableCell>
            <TableCell align="right"></TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {users && users.map((row) => (
            <TableRow key={row.id}>
              <TableCell>{row.id}</TableCell>
              <TableCell>{row.username}</TableCell>
              <TableCell align="right">
                <Button variant="outlined" size="small" onClick={() => {
                  setUserToEdit(row);
                  setShowUserForm(true);
                }}>
                  Edit
                </Button>
              </TableCell>


            </TableRow>
          ))}
        </TableBody>
      </Table>

      {showUserForm && (
        <UserForm title={'Edit user'} user={userToEdit || undefined} onClose={(refresh: boolean) => {
          setUserToEdit(null);
          setShowUserForm(false);

          if (refresh) {
            setUsers([]);
            setUsersLoaded(false);
          }
        }} dataService={props.dataService} />
      )}

    </React.Fragment>
  );
}
