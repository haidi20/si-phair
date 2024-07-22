const { Sequelize, DataTypes } = require('sequelize');
const db = require('../database');
const moment = require('moment');
const Project = require('./project');
const Job = require('./Job');

const JobOrder = db.define('JobOrder', {
    id: {
        type: DataTypes.BIGINT,
        autoIncrement: true,
        primaryKey: true
    },
    project_id: {
        type: DataTypes.BIGINT,
        allowNull: false
    },
    project_name: {
        type: DataTypes.VIRTUAL,
        get() {
            return this.project?.get().name;
        },
        set(/*value*/) {
            throw new Error('Do not try to set the `project_name` value!');
        }
    },
    job_id: {
        type: DataTypes.INTEGER,
    },
    job_name: {
        type: DataTypes.VIRTUAL,
        get() {
            return this.job?.get().name;
        },
        set(/*value*/) {
            throw new Error('Do not try to set the `job_name` value!');
        }
    },
    job_level: {
        type: DataTypes.ENUM('hard', 'middle', 'easy'),
        allowNull: false
    },
    job_note: {
        type: DataTypes.TEXT,
    },
    datetime_start: {
        type: DataTypes.DATE,
        allowNull: false
    },
    datetime_end: {
        type: DataTypes.DATE,
    },
    datetime_estimation_end: {
        type: DataTypes.DATE,
        allowNull: false,
        get() {
            const value = this.getDataValue('datetime_estimation_end');
            if (value) {
                return moment.utc(value).format('YYYY-MM-DD HH:mm');
            }
            return value;
        },
    },
    datetime_estimation_end_before: {
        type: DataTypes.VIRTUAL,
        get() {
            const datetimeEstimationEnd = this.getDataValue('datetime_estimation_end');
            if (datetimeEstimationEnd) {
                return moment.utc(datetimeEstimationEnd).subtract(15, 'minutes').format('YYYY-MM-DD HH:mm');
            }
            return null;
        },
    },
    datetime_estimation_end_readable: {
        type: DataTypes.VIRTUAL,
        get() {
            const value = this.getDataValue('datetime_estimation_end');
            if (value) {
                return moment.utc(value).format('dddd, DD MMMM YYYY HH:mm');
            }
            return value;
        },
    },
    estimation: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    time_type: {
        type: DataTypes.ENUM('minutes', 'hours', 'days'),
        allowNull: false
    },
    category: {
        type: DataTypes.ENUM('reguler', 'daily', 'fixed_price'),
        allowNull: false
    },
    status: {
        type: DataTypes.ENUM('active', 'pending', 'overtime', 'correction', 'finish', 'assessment'),
        allowNull: false
    },
    status_note: {
        type: DataTypes.TEXT,
    },
    note: {
        type: DataTypes.TEXT,
    },
    created_by: {
        type: DataTypes.BIGINT,
    },
    updated_by: {
        type: DataTypes.BIGINT,
    },
    deleted_by: {
        type: DataTypes.BIGINT,
    },
    created_at: {
        type: DataTypes.DATE,
    },
    updated_at: {
        type: DataTypes.DATE,
    },
    deleted_at: {
        type: DataTypes.DATE,
    },
    job_another_name: {
        type: DataTypes.STRING,
    },
    is_assessment_qc: {
        type: DataTypes.BOOLEAN,
        defaultValue: 1
    },
}, {
    tableName: 'job_orders',
    paranoid: true, // Enable soft deletion (paranoid mode)
    underscored: true,  // this option sets field names and relation keys in underscored format instead of camelCase.
    timestamps: true,   // this option states that we are not using Sequelize's built-in timestamps.
});

// Create the association with JobOrder model and define the scope
JobOrder.belongsTo(Project, {
    as: 'project',
    foreignKey: 'project_id', // The column in the "Notification" table that references the "JobOrder" table
    constraints: false, // Set to false if you don't want to enforce foreign key constraints
});
JobOrder.belongsTo(Job, {
    as: 'job',
    foreignKey: 'job_id',
    constraints: false,
});

module.exports = JobOrder;
