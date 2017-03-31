import React from 'react';
import { Dropdown, DropdownMenu, DropdownItem } from 'reactstrap';

const UserNav = ({
  dropdownOpen,
  onToggle,
  onLogout,
  user
}) => (
  <div className="d-flex flex-row-reverse">
    <div className="p-2">
      <Dropdown
        isOpen={dropdownOpen}
        toggle={onToggle}>
        <a
          className="dropdown-toggle"
          data-toggle="dropdown"
          onClick={onToggle}
          aria-expanded="false">
          {user.firstname}
        </a>
        <DropdownMenu right>
          <DropdownItem>Settings</DropdownItem>
          <DropdownItem onClick={onLogout}>
            Log Out
          </DropdownItem>
        </DropdownMenu>
      </Dropdown>
    </div>
  </div>
);

export default UserNav;
