import java.lang.*;
import java.io.*;
import java.util.*;
import java.math.*;
import java.text.*;

class Main{ //it is important that this does not read "public class Main"
    //adds two numbers
    public static void main(String[] args){

        test_lang();
        test_io();
        test_util();
        test_math();
        test_text();

        Scanner reader = new Scanner(System.in);
        int one, two;
        one = reader.nextInt();
		two = reader.nextInt();
		System.out.println(one+two);
    }

    private static void die(String msg){
        System.out.println(msg);
        System.exit(1);
    }

    private static void test_lang(){
        Integer myInt = Integer.valueOf(4);

        if (myInt.longValue() != 4)
            die("Integer test failed");

        String str1 = new String("Foo");
        String str2 = new String("foo");

        if (!str1.equalsIgnoreCase(str2))
            die("String test failed");

        if(StrictMath.abs(-1) != 1)
            die("StrictMath test failed");
    }

    private static void test_io(){
        StringWriter writer = new StringWriter();
        writer.append('a');
        writer.append('b');
        writer.flush();
        StringReader reader = new StringReader(writer.toString());
        try{
            if (reader.read() != 'a')
                die("IO test failed");
        } catch (IOException err) {
            die("IO test produced an error");
        }
    }

    private static void test_util(){
        BitSet bitset1 = new BitSet();
        BitSet bitset2 = new BitSet();

        bitset1.set(1);
        bitset2.set(1);
        if(!bitset1.intersects(bitset2))
            die("Bitset test failed");

        HashMap<Character, Integer> map = new HashMap<Character, Integer>();
        map.put('a', 10);
        map.put('b', 20);
        if(map.get('a') != 10)
            die("HashMap test failed");

        Stack<Integer> stack = new Stack<Integer>();
        stack.push(1);
        stack.push(2);

        if (stack.pop() != 2)
            die("Stack test failed");
    }

    private static void test_math(){
        if(BigDecimal.TEN.compareTo(BigDecimal.TEN) != 0)
            die("BigDecimal test failed");

        BigInteger int1 = new BigInteger("2");
        BigInteger int2 = new BigInteger("4");
        int1 = int1.pow(300);
        int2 = int2.pow(150);

        if(int1.compareTo(int2) != 0)
            die("BigInt test failed");
    }

    //converts from American decimal format to Danish format
    private static void test_text(){
        Locale locale = new Locale("da","DK");
        NumberFormat numberFormat = NumberFormat.getInstance(locale);
        String number = numberFormat.format(42.17);
        
        if(!number.equals("42,17")){
            die("numberFormat test failed");
        }
    }
}

