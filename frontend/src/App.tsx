import React from 'react';
import logo from './logo.svg';
import './App.css';
import Blog from './Blog/Blog';
import DataService from './Service/DataService';
import UserService from './Service/UserService';
import LocalStorage from './Service/Storage/LocalStorage';

function App() {

  // @TODO get url from .env
  const dataService = new DataService('http://127.0.0.1:8888');
  const userService = new UserService(LocalStorage.getObject('user'));

  return (
    <div>
      <Blog dataService={dataService} userService={userService} />
    </div>
  );
}

export default App;
