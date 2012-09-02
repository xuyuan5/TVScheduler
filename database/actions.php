<?php
function setup_db($username, $password, $createDB = true)
{
    $result = false;
    $con = mysql_connect(SERVER_NAME, $username, $password);
    if($con)
    {
        if($createDB)
        {
            // create database
            if(!mysql_query("CREATE DATABASE ".DATABASE_NAME, $con))
            {
                echo "Database create failed!";
            }
            // only create user if it's a new database
            create_user($con);
        }
        // create table
        create_tables($con);
        
        mysql_close($con);
        $result = true;
    }
    return $result;
}

function create_tables(&$con)
{
    mysql_select_db(DATABASE_NAME, $con);
    
    // drop all tables
    $sql = "DROP TABLE IF EXISTS Schedules CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Schedules ".mysql_error());
    }
    
    $sql = "DROP TABLE IF EXISTS IgnoreRegEx CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table IgnoreRegEx ".mysql_error());
    }
    
    $sql = "DROP TABLE IF EXISTS Queries CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Queries ".mysql_error());
    }
    
    $sql = "DROP TABLE IF EXISTS Channels CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table Channels ".mysql_error());
    }
    
    $sql = "DROP TABLE IF EXISTS TimeTable CASCADE";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error dropping table TimeTable ".mysql_error());
    }
    
    // recreate all tables
    $sql = "CREATE TABLE Channels
    (
    Channel varchar(10),
    IconURL text,
    QueryURL text,
    ChannelNumber int,
    Tag varchar(100),
    ProgrammingClassName varchar(100),
    ProgrammingTimeClassName varchar(100),
    ProgrammingNameClassName varchar(100),
    ProgrammingEpClassName varchar(100),
    ProgrammingNewEpClassName varchar(100),
    PRIMARY KEY (Channel),
    UNIQUE (ChannelNumber)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Channels ".mysql_error());
    }
    
    $sql = "CREATE TABLE Queries
    (
    ID int NOT NULL auto_increment,
    Channel varchar(10),
    Name varchar(100),
    Type varchar(10),
    DefaultValue varchar(50),
    FOREIGN KEY (Channel) REFERENCES Channels(Channel) ON DELETE CASCADE,
    PRIMARY KEY (ID)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Queries ".mysql_error());
    }
    
    $sql = "CREATE TABLE IgnoreRegEx
    (
    ID int NOT NULL auto_increment,
    Channel varchar(10),
    RegEx text,
    FOREIGN KEY (Channel) REFERENCES Channels(Channel) ON DELETE CASCADE,
    PRIMARY KEY (ID)
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table IgnoreRegEx ".mysql_error());
    }
    
    $sql = "CREATE TABLE Schedules
    (
    Channel varchar(10),
    StartTime datetime,
    EndTime datetime,
    User varchar(100),
    FOREIGN KEY (Channel) REFERENCES Channels(Channel) ON DELETE CASCADE
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table Schedules ".mysql_error());
    }
    
    $sql = "CREATE TABLE TimeTable
    (
    Channel varchar(10),
    Title varchar(255),
    StartTime bigint unsigned,
    Episode varchar(255),
    IsNewEpisode boolean,
    FOREIGN KEY (Channel) REFERENCES Channels(Channel) ON DELETE CASCADE
    )";
    $result = mysql_query($sql, $con);
    if(!$result)
    {
        die("error creating table TimeTable ".mysql_error());
    }
}

function create_user(&$con)
{
    $sql = "CREATE USER '".DBO_USER_NAME."'@'".SERVER_NAME."' IDENTIFIED BY '".DBO_PASSWORD."'";
    mysql_query($sql, $con);
    $sql = "GRANT ALL ON ".DATABASE_NAME.".* TO '".DBO_USER_NAME."'@'".SERVER_NAME."'";
    mysql_query($sql, $con);
}
?>