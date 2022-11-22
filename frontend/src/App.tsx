import React from 'react';
import logo from './logo.svg';
import './App.css';
import Blog from './Blog/Blog';
import DataService from './Service/DataService';
import UserService from './Service/UserService';
import LocalStorage from './Service/Storage/LocalStorage';
import { API_URL } from './config';

function App() {

  const userService = new UserService(LocalStorage.getObject('user'));
  const dataService = new DataService(API_URL, userService);

  return (
    <div>
      <Blog dataService={dataService} userService={userService} />
    </div>
  );
}

export default App;
