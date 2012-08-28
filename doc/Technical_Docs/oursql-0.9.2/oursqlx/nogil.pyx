# Anything that transmits over the network should be run with nogil. I only 
# wish cython had an easier way of doing this than having all of this stupid
# duplication of functions.

cdef inline MYSQL *mysql_real_connect(
        MYSQL *conn, char *host, char *user, char *passwd, char *db, 
        unsigned int port, char *unix_socket, unsigned long client_flag):
    with nogil:
        return _mysql_real_connect(conn, host, user, passwd, db, port, 
            unix_socket, client_flag)

cdef inline int mysql_ping(MYSQL *conn):
    with nogil:
        return _mysql_ping(conn)

cdef inline my_bool mysql_commit(MYSQL *conn):
    with nogil:
        return _mysql_commit(conn)

cdef inline my_bool mysql_rollback(MYSQL *conn):
    with nogil:
        return _mysql_rollback(conn)

cdef inline int mysql_stmt_prepare(MYSQL_STMT *stmt, 
        char *stmt_str, unsigned long length):
    with nogil:
        return _mysql_stmt_prepare(stmt, stmt_str, length)

cdef inline int mysql_stmt_execute(MYSQL_STMT *stmt):
    with nogil:
        return _mysql_stmt_execute(stmt)

cdef inline my_bool mysql_stmt_send_long_data(MYSQL_STMT *stmt, 
        unsigned int index, char *data, unsigned long length):
    with nogil:
        return _mysql_stmt_send_long_data(stmt, index, data, length)

cdef inline int mysql_stmt_fetch_column(MYSQL_STMT *stmt, 
        MYSQL_BIND *bind, unsigned int column, unsigned long offset):
    with nogil:
        return _mysql_stmt_fetch_column(stmt, bind, column, offset)

cdef inline int mysql_stmt_store_result(MYSQL_STMT *stmt):
    with nogil:
        return _mysql_stmt_store_result(stmt)
