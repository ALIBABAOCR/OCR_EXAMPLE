import time

TIME_ZONE = "GMT"
FORMAT_ISO_8601 = "%Y-%m-%dT%H:%M:%SZ"
FORMAT_RFC_2616 = "%a, %d %b %Y %X GMT"


def get_iso_8061_date():
    return time.strftime(FORMAT_ISO_8601, time.gmtime())


def get_rfc_2616_date():
    return time.strftime(FORMAT_RFC_2616, time.gmtime())


def get_timestamp():
    return str(int(time.time() * 1000))


if __name__ == "__main__":
    print get_iso_8061_date()
    print get_rfc_2616_date()
    print get_timestamp()
