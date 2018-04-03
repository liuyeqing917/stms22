<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>消息发布</title>
    <script type="text/javascript">
        function pub(){
            alert("发布成功");
        }
    </script>
</head>
<body>
<center>
    <form action="{{route("messageHandle")}}" method="post">
        <br>接受对象:<input type="checkbox" name="receive_type_tea" value="2"/>教师<input type="checkbox" name="receive_type_stu" value="1"/>学生</br>
        <br>
        方向科室:<select name="department_one">
            <option value="内科">内科</option>
            <option value="外科">外科</option>
            <option value="急诊科">急诊科</option>
            <option value="儿科">儿科</option>
            <option value="妇科">妇科</option>
            <option value="麻醉科">麻醉科</option>
        </select>
        科室:<select name="department_two">
            <option value="骨科">骨科</option>
            <option value="普通外科">普通外科</option>
            <option value="神经外科">神经外科</option>
            <option value="心胸外科">心胸外科</option>
        </select>
        </br>
        <br>
        标题:<input type="text" size="50" name="title"/>
        </br>
        <br>
        内容:<textarea rows="5" cols="40" name="content"></textarea>
        </br>
        <br>
        <input type="submit" value="发布" onclick="pub()"/>
        </br>
    </form>
</center>
</body>
</html>