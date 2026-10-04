FROM jupyter/scipy-notebook:latest

USER ${NB_UID}

RUN pip install --no-cache-dir \
    psycopg2-binary \
    sqlalchemy