// GuestLayout.js
import { Outlet } from 'react-router-dom';
import { useEffect } from 'react';

const HomeLayout = () => {

  return (
    <>
      <Outlet />
    </>
  );
};

export default HomeLayout;
