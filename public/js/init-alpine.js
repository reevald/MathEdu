function htmlData(){
  return {
    // Menu handler
    is_menu_open: false,
    toggleMenu(){
      this.is_menu_open = !this.is_menu_open;
    },
    closeMenu(){
      this.is_menu_open = false;
    },
    // Size handler
    is_size_pen_open: false,
    toggleSizePen(){
      this.is_size_pen_open = !this.is_size_pen_open;
    },
    closeSizePen(){
      this.is_size_pen_open = false;
    },
    // Color handler
    is_color_pen_open: false,
    toggleColorPen(){
      this.is_color_pen_open = !this.is_color_pen_open;
    },
    closeColorPen(){
      this.is_color_pen_open = false;
    }
  }
}